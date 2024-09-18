<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SoapClient;
use GuzzleHttp\Pool;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\MultipartStream;
use URL;
use Artisan;
use Image;
use Mail;
use \Carbon\Carbon;
use DB;
use Storage;
use App\Models\User;
use App\Models\Image as ImageModel;

class Helper
{
    public static $version = '1.0.0';
    public static $css_asset_version = '1.0.0';
    public static $js_asset_version = '1.0.0';
    public static $images_asset_version = '1.0.0';
    public static $currency_symbol = '£';
    public static $credit_safe_base_url = 'https://connect.creditsafe.com/v1/';
    public static $site_home_url = 'https://fundingaltdev.co.uk/';
    public static $site_admin_url = 'https://fundingaltdev.co.uk/wp-admin/';

    public static function getSiteTitle($title = null, $with_title = true, $separator = ' - ')
    {
        $app_name = config('app.name');

        if (empty($app_name))
        {
            return $title;
        }

        if ($title)
        {
            return $with_title ? $title . $separator . $app_name : $title;
        }
        
        return $app_name;
    }

    public static function sendEmail($view, $data = [], $to, $subject, $attachments = array(), $from = null, $email_title_name = null)
    {
        try
        {
            Mail::send($view, $data, function($message) use ($to, $subject, $attachments, $from, $email_title_name) {

                if (!empty($attachments))
                {
                    foreach ($attachments as $key => $attachment)
                    {
                        $file = $attachment['file'];

                        $name = null;
                        $ext = null;

                        if (isset($attachment['name']) && $attachment['ext'])
                        {
                            $name = $attachment['name'] . '.' . $attachment['ext'];
                            $ext = $attachment['ext'];
                        }

                        if (!empty($name))
                        {
                            $message->attach($file, array(
                                    'as' => $name,
                                    'mime' => $ext
                                )
                            );
                        }
                        else
                        {
                            $message->attach($file);
                        }
                    }
                }

                if (empty($from))
                {
                    $from = config('mail.from.address');
                }

                if (empty($email_title_name))
                {
                    $email_title_name = config('mail.from.name');
                }

                if (!empty($from) && !empty($email_title_name))
                {
                    $message->from($from, $email_title_name);
                }

                $message->to($to);
                $message->subject($subject);
            });
        }
        catch(\Exception $e)
        {
            report($e);
        }
    }

    public static function getRandomKey($length = 30)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public static function createSlug($name)
    {
        return Str::slug($name);
    }

    public static function getSlug($name, $table = null, $id = null)
    {
        $slug = Str::slug($name);
        
        $data = DB::table($table)->where('slug', $slug)->where('id', '!=', $id)->first();

        if (!empty($data))
        {
            return $slug . '-' . $id;
        }

        return $slug;
    }

    public static function getUUID($table = null, $column = null)
    {
        $uuid = (string) Str::uuid();

        if (!empty($table) && !empty($column))
        {
            $data = 1;

            while (!empty($data))
            {
                $uuid = (string) Str::uuid();
                $data = DB::table($table)->where($column, $uuid)->first();
            }
        }

        return $uuid;
    }

    public static function getInputValue($key = null, $value = null, $is_collection = true, $default = null)
    {
        if (empty($key) && empty($value))
        {
            return $default;
        }

        if (old($key))
        {
            return old($key);
        }

        if (!empty($value))
        {
            if ($is_collection)
            {
                if (is_array($value) && isset($value[$key]))
                {
                    return $value[$key];
                }
                elseif (is_object($value) && isset($value->$key))
                {
                    return $value->$key;
                }
            }
            else
            {
                return $value;
            }
        }

        return $default;
    }

    public static function storeUploadedFile($file, $target_dir = null, $name = null, $full_path = true, $disk = 'public')
    {
        $extension = '.' . strtolower($file->getClientOriginalExtension());

        if (empty($name))
        {
            $name = self::getRandomKey() . $extension;

            while (empty($name) || Storage::disk($disk)->exists($target_dir . $name))
            {
                $name = self::getRandomKey() . $extension;
            }
        }
        else
        {
            $name .= $extension;
        }

        if ($disk == 'public')
        {
            $file->move(storage_path('app/public/' . $target_dir), $name);
        }
        else
        {
            $file->move(storage_path('app/' . $target_dir), $name);
        }

        if ($full_path)
        {
            return $target_dir . $name;
        }

        return $name;
    }

    public static function getIp()
    {
        $ip = null;

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
        {
            if (array_key_exists($key, $_SERVER) === true)
            {
                foreach (explode(',', $_SERVER[$key]) as $ip)
                {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                    {
                        if (empty($ip))
                        {
                            $ip = '127.0.0.1';
                        }
                    }
                }
            }
        }

        return $ip;
    }

    public static function createUpdateOption($option_name, $option_value = null)
    {
        if (is_array($option_value) || is_object($option_value))
        {
            $option_value = serialize($option_value);
        }

        $meta = DB::table('wp_options')->where('option_name', $option_name)->first();

        if (empty($meta))
        {
            return DB::table('wp_options')->insertGetId( [
                'option_name' => $option_name,
                'option_value' => $option_value,
            ] );
        }

        DB::table('wp_options')->where('option_name', $option_name)->update( [
            'option_value' => $option_value,
        ] );

        return;
    }

    public static function deleteOption($option_name)
    {
        DB::table('wp_options')->where('option_name', $option_name)->delete();
    }

    public static function getOption($option_name)
    {
        $option = DB::table('wp_options')->where('option_name', $option_name)->first();

        if (empty($option) || empty($option->option_value))
        {
            return null;
        }

        $option_value = @unserialize($option->option_value);

        if ($option_value !== false)
        {
            return $option_value;
        }

        return $option->option_value;
    }

    public static function displayPrice($price, $add_dash_on_zero = false)
    {
        if (!is_numeric($price))
        {
            return;
        }

        if ($add_dash_on_zero && $price == 0)
        {
            return '-';
        }

        if ($price < 0)
        {
            $price = str_replace('-', '', $price);
            $price = '-' . self::$currency_symbol . number_format($price, 2, '.', ',');
        }
        else
        {
            $price = self::$currency_symbol . number_format($price, 2, '.', ',');
        }

        return $price;
    }

    public static function getListOfTimezone()
    {
        $timezones = \DateTimeZone::listIdentifiers();

        $all_zones = [];

        foreach($timezones as $timezone)
        {
            $timezone_label = isset(explode('/', $timezone)[1]) ? explode('/', $timezone)[1] : null;
            $timezone_continent = explode('/', $timezone)[0];

            if (!empty($timezone_label))
            {
                $all_zones[$timezone_continent][$timezone] = $timezone_label;
            }
        }

        return $all_zones;
    }

    public static function displayNumber($price, $decimal = 2, $separator = ',')
    {
        if (!is_numeric($price))
        {
            return $price;
        }

        return number_format($price, $decimal, '.', $separator);
    }

    public static function getDatatablesForSOP($table_data, $with_select_all = 1, $class = 'nowrap', $fixed_height = false)
    {
        $fixed_height = $fixed_height ? 'h-750-with-ovf' : '';

        $table = '<div class="table-responsive ' . $fixed_height . '">
                    <table class="table w-100 datatable-sop '. $class .'">
                        <thead>
                            <tr>';

        if (!empty($with_select_all))
        {
            $table .= '<th>
                            <div class="form-check form-checkbox-dark">
                                <input type="checkbox" class="form-check-input select-all-checkbox-for-sop" id="select-all-checkbox-for-sop">
                                <label class="form-check-label" for="select-all-checkbox-for-sop">&nbsp;</label>
                            </div>
                        </th>';
        }

        foreach ($table_data as $key => $th)
        {
            $table .= '<th>' . __($th) . '</th>';
        }

        $total_columns = count($table_data);

        if (!empty($with_select_all))
        {
            $total_columns++;
        }

        $table .= '</tr>
                </thead>
                <tbody>
                <tr class="total_columns" colspan="'. $total_columns .'" class="text-center">
                <td>Loading...</td>
                </tr>
                </tbody>
            </table>
        </div>';

        return $table;
    }

    public static function getDatatables($table_data, $with_select_all = 1, $class = 'nowrap', $fixed_height = false)
    {
        $fixed_height = $fixed_height ? 'h-750-with-ovf' : '';

        $table = '<div class="table-responsive ' . $fixed_height . '">
                    <table class="table w-100 datatable '. $class .'">
                        <thead>
                            <tr>';

        if (!empty($with_select_all))
        {
            $table .= '<th>
                            <div class="form-check form-checkbox-dark">
                                <input type="checkbox" class="form-check-input select-all-checkbox" id="select-all-checkbox">
                                <label class="form-check-label" for="select-all-checkbox">&nbsp;</label>
                            </div>
                        </th>';
        }

        foreach ($table_data as $key => $th)
        {
            $table .= '<th>' . __($th) . '</th>';
        }

        $total_columns = count($table_data);

        if (!empty($with_select_all))
        {
            $total_columns++;
        }

        $table .= '</tr>
                </thead>
                <tbody>
                <tr class="total_columns" colspan="'. $total_columns .'" class="text-center">
                <td>Loading...</td>
                </tr>
                </tbody>
            </table>
        </div>';

        return $table;
    }

    public static function generateAvatarSVG($name, $width = 32, $length = 1, $font_size = 0.35, $color = '#6c757d', $background = '#f1f1f1')
    {
        $avatar = new \LasseRafn\InitialAvatarGenerator\InitialAvatar();
        return $avatar->name(trim($name))->length($length)->fontSize($font_size)->color($color)->background($background)->rounded()->width($width)->generateSvg()->toXMLString();
    }

    public static function allowedImagesExtensions()
    {
        return ['png', 'jpg', 'gif', 'jpeg', 'webp', 'svg'];
    }

    public static function downloadImage($url)
    {
        if (!isset(pathinfo($url)['extension']) || empty(pathinfo($url)['extension']) || !in_array(strtolower(pathinfo($url)['extension']), self::allowedImagesExtensions()))
        {
            return;
        }

        $image_content = @file_get_contents($url);

        if ($image_content === false)
        {
            return;
        }

        $name = self::getRandomKey() . '.' . pathinfo($url)['extension'];

        while (empty($name) || Storage::disk('public')->exists('images/' . date('Y') . '/' . $name))
        {
            $name = self::getRandomKey() . '.' . pathinfo($url)['extension'];
        }

        Storage::disk('public')->put('images/' . date('Y') . '/' . $name, $image_content);

        return self::createImage(pathinfo($url)['basename'], 'images/' . date('Y') . '/' . $name);
    }

    public static function formatBytes($size, $precision = 2)
    { 
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        
        return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffix[$f_base]; 
    }

    public static function getCreditSafeToken($bypass_cache = false)
    {
        if (!$bypass_cache)
        {
            $credit_safe_token = self::getOption('credit_safe_token');

            if (!empty($credit_safe_token))
            {
                return $credit_safe_token;
            }
        }

        $ga_cs_username = self::getOption('ga_cs_username');
        $ga_cs_password = self::getOption('ga_cs_password');

        if (empty($ga_cs_username) || empty($ga_cs_password))
        {
            return;
        }

        $payload = [
            'username' => $ga_cs_username,
            'password' => $ga_cs_password,
        ];

        $ch = curl_init( Helper::$credit_safe_base_url . 'authenticate' );

        $payload = json_encode( $payload );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);

        if (empty($result))
        {
            return;
        }

        $result = json_decode($result, true);

        if (!isset($result['token']) || empty($result['token']))
        {
            return;
        }

        self::createUpdateOption('credit_safe_token', $result['token']);

        return $result['token'];
    }

    public static function getDirectorSearchResults($first_name = null, $last_name = null, $dob = null, $countries = 'GB', $page = 1)
    {
        $bearer_token = self::getCreditSafeToken();

        if (empty($bearer_token))
        {
            return;
        }

        $request_paramteres = [];

        if (!empty($first_name))
        {
            $request_paramteres['firstName'] = $first_name;
        }

        if (!empty($last_name))
        {
            $request_paramteres['lastName'] = $last_name;
        }

        if (!empty($dob))
        {
            $request_paramteres['dateOfBirth'] = $dob;
        }

        $request_paramteres['page'] = $page;
        $request_paramteres['pageSize'] = 200;
        $request_paramteres['countries'] = strtoupper($countries);

        $request_paramteres = http_build_query($request_paramteres);

        $ch = curl_init( Helper::$credit_safe_base_url . 'people?' . $request_paramteres );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $bearer_token ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $director_info = curl_exec($ch);
        curl_close($ch);

        if (empty($director_info))
        {
            return;
        }

        $director_info = json_decode($director_info, true);

        if (isset($director_info['error']) && !empty($director_info['error']) && strtolower($director_info['error']) == 'invalid token.')
        {
            $bearer_token = self::getCreditSafeToken(true);
            return Helper::getDirectorSearchResults($first_name, $last_name, $dob, $countries, $page);
        }

        if (!isset($director_info['directors']) || empty($director_info['directors']))
        {
            return;
        }

        return $director_info['directors'];
    }

    public static function getDirectorDetails($people_id)
    {
        $bearer_token = self::getCreditSafeToken();

        if (empty($bearer_token))
        {
            return;
        }

        $ch = curl_init( Helper::$credit_safe_base_url . 'people/' . $people_id );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $bearer_token ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $director_info = curl_exec($ch);
        curl_close($ch);

        if (empty($director_info))
        {
            return;
        }

        $director_info = json_decode($director_info, true);

        if (isset($director_info['error']) && !empty($director_info['error']) && strtolower($director_info['error']) == 'invalid token.')
        {
            $bearer_token = self::getCreditSafeToken(true);
            return Helper::getDirectorDetails($people_id);
        }

        if (!isset($director_info['report']) || empty($director_info['report']))
        {
            return;
        }

        return $director_info['report'];
    }

    public static function getResultsOfCreditSafe($reg_no, $countries = 'GB', $name_string = null)
    {
        $bearer_token = self::getCreditSafeToken();

        if (empty($bearer_token))
        {
            return;
        }

        if (!empty($name_string))
        {
            $ch = curl_init( Helper::$credit_safe_base_url . 'companies?name='. urlencode($reg_no) .'&countries=' . strtoupper($countries) );
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $bearer_token ));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $company_info = curl_exec($ch);
            curl_close($ch);

            if (empty($company_info))
            {
                return;
            }

            $company_info = json_decode($company_info, true);

            if (isset($company_info['error']) && !empty($company_info['error']) && strtolower($company_info['error']) == 'invalid token.')
            {
                $bearer_token = self::getCreditSafeToken(true);
                return Helper::getResultsOfCreditSafe($reg_no, $countries, $name_string);
            }

            if (!isset($company_info['companies']) || empty($company_info['companies']))
            {
                return;
            }

            foreach ($company_info['companies'] as $key => $company)
            {
                if (strtoupper(trim($company['name'])) == strtoupper(trim($reg_no)))
                {
                    $reg_no = trim($company['regNo']);
                    break;
                }
            }
        }

        $ch = curl_init( Helper::$credit_safe_base_url . 'companies?regNo='. $reg_no .'&countries=' . strtoupper($countries) );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $bearer_token ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $company_info = curl_exec($ch);
        curl_close($ch);

        if (empty($company_info))
        {
            return;
        }

        $company_info = json_decode($company_info, true);

        if (isset($company_info['error']) && !empty($company_info['error']) && strtolower($company_info['error']) == 'invalid token.')
        {
            $bearer_token = self::getCreditSafeToken(true);
            return Helper::getResultsOfCreditSafe($reg_no, $countries, $name_string);
        }

        if (!isset($company_info['companies']) || empty($company_info['companies']))
        {
            return;
        }

        $ch = curl_init( Helper::$credit_safe_base_url . 'companies/' . $company_info['companies'][0]['id'] );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer " . $bearer_token ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $company_details = curl_exec($ch);
        curl_close($ch);

        if (empty($company_details))
        {
            return;
        }

        $company_details = json_decode($company_details, true);

        if (isset($company_details['error']) && !empty($company_details['error']) && strtolower($company_details['error']) == 'invalid token.')
        {
            $bearer_token = self::getCreditSafeToken(true);
            return Helper::getResultsOfCreditSafe($reg_no, $countries, $name_string);
        }

        if (empty($company_details) || !isset($company_details['report']) || empty($company_details['report']))
        {
            return;
        }

        return [
            'company_info' => $company_info,
            'company_details' => $company_details,
        ];
    }

    public static function createAndReturnColumnNames(&$db_columns, $main_index, $main_index_name = null, $only_string_indexes = false)
    {
        foreach ($main_index as $key => $value)
        {
            if (!is_array($value))
            {
                if (!empty($main_index_name))
                {
                    $db_columns[$main_index_name . '_' . $key] = $value;
                }
                else
                {
                    $db_columns[$key] = $value;
                }
            }
            else
            {
                if (!$only_string_indexes)
                {
                    if (!empty($main_index_name))
                    {
                        $index_main_col = $main_index_name . '_' . $key;   
                    }
                    else
                    {
                        $index_main_col = $key; 
                    }

                    self::createAndReturnColumnNames($db_columns, $value, $index_main_col);
                }
            }
        }
    }

    public static function updateCreditSafeCompanyData($registration_number, $company_details, $ga_credit_safe_db_columns = null)
    {
        if (empty($ga_credit_safe_db_columns))
        {
            $ga_credit_safe_db_columns = Helper::getOption('ga_credit_safe_db_columns');

            if (!empty($ga_credit_safe_db_columns))
            {
                $ga_credit_safe_db_columns = array_keys($ga_credit_safe_db_columns);
            }
        }

        if (empty($ga_credit_safe_db_columns))
        {
            return;
        }

        $db_columns = $insert_values = [];

        self::createAndReturnColumnNames($db_columns, $company_details['report']['companySummary'], 'compSum');

        if (isset($company_details['report']['companyIdentification']['basicInformation']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['companyIdentification']['basicInformation'], 'compIdentiBasicInfo');    
        }

        if (isset($company_details['report']['creditScore']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['creditScore'], 'credSco');
        }

        if (isset($company_details['report']['paymentData']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['paymentData'], 'paymentData');
        }

        if (isset($company_details['report']['negativeInformation']['ccjSummary']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['negativeInformation']['ccjSummary'], 'negativeInfoCcjSum');    
        }

        if (isset($company_details['report']['additionalInformation']['miscellaneous']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['additionalInformation']['miscellaneous'], 'misc');    
        }

        if (isset($company_details['report']['additionalInformation']['mortgageSummary']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['additionalInformation']['mortgageSummary'], 'mortgageSum');
        }

        if (isset($company_details['report']['additionalInformation']['landRegistry']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['additionalInformation']['landRegistry'], 'landRegistry');
        }

        if (isset($company_details['report']['additionalInformation']['enquiriesTrend']))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['additionalInformation']['enquiriesTrend'], 'enquiriesTrend');    
        }

        if (isset($company_details['report']['additionalInformation']['scoreTiers'][0]))
        {
            self::createAndReturnColumnNames($db_columns, $company_details['report']['additionalInformation']['scoreTiers'][0], 'scoreTiers');
        }

        foreach ($db_columns as $key => $col)
        {
            if (in_array($key, $ga_credit_safe_db_columns))
            {
                $insert_values[$key] = $col;
            }
        }

        $ga_credit_safe = DB::table('ga_credit_safe')->where('compSum_companyRegistrationNumber', $registration_number)->first();

        if (!empty($ga_credit_safe))
        {
            DB::table('ga_credit_safe')->where('id', $ga_credit_safe->id)->update($insert_values);

            $credit_safe_id = $ga_credit_safe->id;
        }
        else
        {
            $credit_safe_id = DB::table('ga_credit_safe')->insertGetId($insert_values);
        }

        $insert_values = [];

        DB::table('ga_credit_safe_activity_classifications')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['companyIdentification']['activityClassifications']) && !empty($company_details['report']['companyIdentification']['activityClassifications']))
        {
            foreach ($company_details['report']['companyIdentification']['activityClassifications'] as $key => $classification)
            {
                if (isset($classification['activities']) && !empty($classification['activities']))
                {
                    foreach ($classification['activities'] as $key => $activity)
                    {
                        $insert_values = [
                            'ga_credit_safe_id' => $credit_safe_id,
                            'classification' => $classification['classification'],
                            'activity_code' => $activity['code'],
                            'activity_description' => $activity['description'],
                        ];

                        DB::table('ga_credit_safe_activity_classifications')->insertGetId($insert_values);
                    }
                }
            }
        }

        DB::table('ga_credit_safe_addresses')->where('ga_credit_safe_id', $credit_safe_id)->where('main_address', 1)->delete();

        if (isset($company_details['report']['contactInformation']['mainAddress']) && !empty($company_details['report']['contactInformation']['mainAddress'])) 
        {
            $insert_values = [
                'ga_credit_safe_id' => $credit_safe_id,
                'main_address' => 1,
            ];

            if (isset($company_details['report']['contactInformation']['mainAddress']['type']))
            {
                $insert_values['type'] = $company_details['report']['contactInformation']['mainAddress']['type'];
            }

            if (isset($company_details['report']['contactInformation']['mainAddress']['simpleValue']))
            {
                $insert_values['simple_value'] = $company_details['report']['contactInformation']['mainAddress']['simpleValue'];
            }

            if (isset($company_details['report']['contactInformation']['mainAddress']['postalCode']))
            {
                $insert_values['postal_code'] = $company_details['report']['contactInformation']['mainAddress']['postalCode'];
            }

            if (isset($company_details['report']['contactInformation']['mainAddress']['telephone']))
            {
                $insert_values['telephone'] = $company_details['report']['contactInformation']['mainAddress']['telephone'];
            }

            if (isset($company_details['report']['contactInformation']['mainAddress']['directMarketingOptOut']) && $company_details['report']['contactInformation']['mainAddress']['directMarketingOptOut'])
            {
                $insert_values['direct_marketing_opt_out'] = 1;
            }

            DB::table('ga_credit_safe_addresses')->insertGetId($insert_values);
        }

        DB::table('ga_credit_safe_addresses')->where('ga_credit_safe_id', $credit_safe_id)->where('main_address', 0)->delete();

        if (isset($company_details['report']['contactInformation']['otherAddresses']) && !empty($company_details['report']['contactInformation']['otherAddresses']))
        {
            foreach ($company_details['report']['contactInformation']['otherAddresses'] as $key => $addr)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                ];

                if (isset($addr['type']))
                {
                    $insert_values['type'] = $addr['type'];
                }

                if (isset($addr['simpleValue']))
                {
                    $insert_values['simple_value'] = $addr['simpleValue'];
                }

                if (isset($addr['postalCode']))
                {
                    $insert_values['postal_code'] = $addr['postalCode'];
                }

                if (isset($addr['telephone']))
                {
                    $insert_values['telephone'] = $addr['telephone'];
                }

                if (isset($addr['directMarketingOptOut']) && $addr['directMarketingOptOut'])
                {
                    $insert_values['direct_marketing_opt_out'] = 1;
                }
                
                DB::table('ga_credit_safe_addresses')->insertGetId($insert_values);
            }
        }

        $insert_values = [];

        DB::table('ga_credit_safe_share_holders')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['shareCapitalStructure']['shareHolders']) && !empty($company_details['report']['shareCapitalStructure']['shareHolders']))
        {
            foreach ($company_details['report']['shareCapitalStructure']['shareHolders'] as $key => $share_holder)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                ];

                if (isset($share_holder['name']))
                {
                    $insert_values['name'] = $share_holder['name'];
                }

                if (isset($share_holder['shareholderType']))
                {
                    $insert_values['share_holder_type'] = $share_holder['shareholderType'];
                }

                if (isset($share_holder['shareType']))
                {
                    $insert_values['share_type'] = $share_holder['shareType'];
                }

                if (isset($share_holder['currency']))
                {
                    $insert_values['currency'] = $share_holder['currency'];
                }

                if (isset($share_holder['totalValueOfSharesOwned']))
                {
                    $insert_values['total_value_of_shares_owned'] = $share_holder['totalValueOfSharesOwned'];
                }

                if (isset($share_holder['totalNumberOfSharesOwned']))
                {
                    $insert_values['total_number_of_shares_owned'] = $share_holder['totalNumberOfSharesOwned'];
                }

                if (isset($share_holder['percentSharesHeld']))
                {
                    $insert_values['percent_shares_held'] = $share_holder['percentSharesHeld'];
                }

                if (isset($share_holder['shareClasses'][0]['shareType']))
                {
                    $insert_values['share_classes_share_type'] = $share_holder['shareClasses'][0]['shareType'];
                }

                if (isset($share_holder['shareClasses'][0]['currency']))
                {
                    $insert_values['share_classes_currency'] = $share_holder['shareClasses'][0]['currency'];
                }

                if (isset($share_holder['shareClasses'][0]['valuePerShare']))
                {
                    $insert_values['share_classes_value_per_share'] = $share_holder['shareClasses'][0]['valuePerShare'];
                }

                if (isset($share_holder['shareClasses'][0]['numberOfSharesOwned']))
                {
                    $insert_values['share_classes_value_of_shares_owned'] = $share_holder['shareClasses'][0]['numberOfSharesOwned'];
                }

                if (isset($share_holder['shareClasses'][0]['valueOfSharesOwned']))
                {
                    $insert_values['share_classes_number_of_shares_owned'] = $share_holder['shareClasses'][0]['valueOfSharesOwned'];
                }

                DB::table('ga_credit_safe_share_holders')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_directors')->where('ga_credit_safe_id', $credit_safe_id)->where('current_director', 1)->delete();

        $insert_values = [];

        if (isset($company_details['report']['directors']['currentDirectors']) && !empty($company_details['report']['directors']['currentDirectors']))
        {
            foreach ($company_details['report']['directors']['currentDirectors'] as $key => $director)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                    'current_director' => 1,
                ];

                if (isset($director['id']))
                {
                    $insert_values['director_id'] = $director['id'];
                }

                if (isset($director['idType']))
                {
                    $insert_values['director_id_type'] = $director['idType'];
                }

                if (isset($director['name']))
                {
                    $insert_values['name'] = $director['name'];
                }

                if (isset($director['title']))
                {
                    $insert_values['title'] = $director['title'];
                }

                if (isset($director['firstName']))
                {
                    $insert_values['first_name'] = $director['firstName'];
                }

                if (isset($director['surname']))
                {
                    $insert_values['surname'] = $director['surname'];
                }

                if (isset($director['address']['type']))
                {
                    $insert_values['address_type'] = $director['address']['type'];
                }

                if (isset($director['address']['simpleValue']))
                {
                    $insert_values['address_simple_value'] = $director['address']['simpleValue'];
                }

                if (isset($director['address']['street']))
                {
                    $insert_values['address_street'] = $director['address']['street'];
                }

                if (isset($director['address']['city']))
                {
                    $insert_values['address_city'] = $director['address']['city'];
                }

                if (isset($director['address']['postalCode']))
                {
                    $insert_values['address_postal_code'] = $director['address']['postalCode'];
                }

                if (isset($director['gender']))
                {
                    $insert_values['gender'] = $director['gender'];
                }

                if (isset($director['dateOfBirth']))
                {
                    $insert_values['date_of_birth'] = $director['dateOfBirth'];
                }

                if (isset($director['nationality']))
                {
                    $insert_values['nationality'] = $director['nationality'];
                }

                if (isset($director['directorType']))
                {
                    $insert_values['director_type'] = $director['directorType'];
                }

                if (isset($director['positions'][0]['dateAppointed']))
                {
                    $insert_values['date_appointed'] = $director['positions'][0]['dateAppointed'];
                }

                if (isset($director['positions'][0]['positionName']))
                {
                    $insert_values['position_name'] = $director['positions'][0]['positionName'];
                }

                if (isset($director['additionalData']['presentAppointments']))
                {
                    $insert_values['present_appointments'] = $director['additionalData']['presentAppointments'];
                }

                if (isset($director['additionalData']['occupation']))
                {
                    $insert_values['occupation'] = $director['additionalData']['occupation'];
                }

                if (isset($director['resignationDate']) && !empty($director['resignationDate']))
                {
                    $insert_values['resignation_date'] = date('Y-m-d H:m:s', strtotime($director['resignationDate']));
                }

                DB::table('ga_credit_safe_directors')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_directors')->where('ga_credit_safe_id', $credit_safe_id)->where('current_director', 0)->delete();

        if (isset($company_details['report']['directors']['previousDirectors']) && !empty($company_details['report']['directors']['previousDirectors']))
        {
            foreach ($company_details['report']['directors']['previousDirectors'] as $key => $director)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                    'current_director' => 0,
                ];

                if (isset($director['id']))
                {
                    $insert_values['director_id'] = $director['id'];
                }

                if (isset($director['idType']))
                {
                    $insert_values['director_id_type'] = $director['idType'];
                }

                if (isset($director['name']))
                {
                    $insert_values['name'] = $director['name'];
                }

                if (isset($director['title']))
                {
                    $insert_values['title'] = $director['title'];
                }

                if (isset($director['firstName']))
                {
                    $insert_values['first_name'] = $director['firstName'];
                }
                elseif (isset($director['name']) && !empty($director['name']))
                {
                    $insert_values['first_name'] = explode(' ', $director['name'])[0];
                }

                if (isset($director['surname']))
                {
                    $insert_values['surname'] = $director['surname'];
                }
                elseif (isset($director['name']))
                {
                    $director_full_name = explode(' ', $director['name']);
                    $insert_values['surname'] = isset($director_full_name[1]) ? $director_full_name[1] : null;
                }

                if (isset($director['address']['type']))
                {
                    $insert_values['address_type'] = $director['address']['type'];
                }

                if (isset($director['address']['simpleValue']))
                {
                    $insert_values['address_simple_value'] = $director['address']['simpleValue'];
                }

                if (isset($director['address']['street']))
                {
                    $insert_values['address_street'] = $director['address']['street'];
                }

                if (isset($director['address']['city']))
                {
                    $insert_values['address_city'] = $director['address']['city'];
                }

                if (isset($director['address']['postalCode']))
                {
                    $insert_values['address_postal_code'] = $director['address']['postalCode'];
                }

                if (isset($director['gender']))
                {
                    $insert_values['gender'] = $director['gender'];
                }

                if (isset($director['dateOfBirth']))
                {
                    $insert_values['date_of_birth'] = $director['dateOfBirth'];
                }

                if (isset($director['nationality']))
                {
                    $insert_values['nationality'] = $director['nationality'];
                }

                if (isset($director['directorType']))
                {
                    $insert_values['director_type'] = $director['directorType'];
                }

                if (isset($director['positions'][0]['dateAppointed']))
                {
                    $insert_values['date_appointed'] = $director['positions'][0]['dateAppointed'];
                }

                if (isset($director['positions'][0]['positionName']))
                {
                    $insert_values['position_name'] = $director['positions'][0]['positionName'];
                }

                if (isset($director['additionalData']['presentAppointments']))
                {
                    $insert_values['present_appointments'] = $director['additionalData']['presentAppointments'];
                }

                if (isset($director['additionalData']['occupation']))
                {
                    $insert_values['occupation'] = $director['additionalData']['occupation'];
                }

                if (isset($director['resignationDate']) && !empty($director['resignationDate']))
                {
                    $insert_values['resignation_date'] = date('Y-m-d H:m:s', strtotime($director['resignationDate']));
                }

                DB::table('ga_credit_safe_directors')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_financial_statements')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['financialStatements']) && !empty($company_details['report']['financialStatements']))
        {
            foreach ($company_details['report']['financialStatements'] as $key => $statement)
            {
                $db_columns = [];

                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                ];
                
                self::createAndReturnColumnNames($db_columns, $statement);

                foreach ($db_columns as $i => $col)
                {
                    if (in_array($i, $ga_credit_safe_db_columns))
                    {
                        $insert_values[$i] = $col;
                    }
                }

                DB::table('ga_credit_safe_financial_statements')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_local_financial_statements')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['localFinancialStatements']) && !empty($company_details['report']['localFinancialStatements']))
        {
            foreach ($company_details['report']['localFinancialStatements'] as $key => $statement)
            {
                $db_columns = [];

                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                ];
                
                self::createAndReturnColumnNames($db_columns, $statement);

                foreach ($db_columns as $i => $col)
                {
                    if (in_array($i, $ga_credit_safe_db_columns))
                    {
                        $insert_values[$i] = $col;
                    }
                }

                DB::table('ga_credit_safe_local_financial_statements')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_county_court_judgements')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['negativeInformation']['countyCourtJudgements']['registered']['exact']) && !empty($company_details['report']['negativeInformation']['countyCourtJudgements']['registered']['exact']))
        {
            foreach ($company_details['report']['negativeInformation']['countyCourtJudgements']['registered']['exact'] as $key => $statement)
            {
                $db_columns = [];

                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                ];
                
                self::createAndReturnColumnNames($db_columns, $statement);

                foreach ($db_columns as $i => $col)
                {
                    if (in_array($i, $ga_credit_safe_db_columns))
                    {
                        $insert_values[$i] = $col;
                    }
                }

                DB::table('ga_credit_safe_county_court_judgements')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_status_history')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['additionalInformation']['statusHistory']) && !empty($company_details['report']['additionalInformation']['statusHistory']))
        {
            foreach ($company_details['report']['additionalInformation']['statusHistory'] as $key => $classification)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                    'history_date' => $classification['date'],
                    'description' => $classification['description'],
                ];

                DB::table('ga_credit_safe_status_history')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_company_history')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['additionalInformation']['companyHistory']) && !empty($company_details['report']['additionalInformation']['companyHistory']))
        {
            foreach ($company_details['report']['additionalInformation']['companyHistory'] as $key => $classification)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                    'history_date' => $classification['date'],
                    'description' => $classification['description'],
                ];

                DB::table('ga_credit_safe_company_history')->insertGetId($insert_values);
            }
        }

        DB::table('ga_credit_safe_credit_limit_history')->where('ga_credit_safe_id', $credit_safe_id)->delete();

        if (isset($company_details['report']['additionalInformation']['creditLimitHistory']) && !empty($company_details['report']['additionalInformation']['creditLimitHistory']))
        {
            foreach ($company_details['report']['additionalInformation']['creditLimitHistory'] as $key => $classification)
            {
                $insert_values = [
                    'ga_credit_safe_id' => $credit_safe_id,
                    'history_date' => $classification['date'],
                    'companyValue_currency' => $classification['companyValue']['currency'],
                    'companyValue_value' => $classification['companyValue']['value'],
                ];

                DB::table('ga_credit_safe_credit_limit_history')->insertGetId($insert_values);
            }
        }

        return $credit_safe_id;
    }

    public static function plaidConfigInfo()
    {
        $test_mode = false;

        if (empty(self::getOption('ga_plaid_mode'))) 
        {
            return null;
        }

        $url = 'https://'. strtolower(self::getOption('ga_plaid_mode')) .'.plaid.com';

        $config = [
                    "client_id" => self::getOption('ga_plaid_client_id'),
                    "secret" => self::getOption('ga_plaid_secret'),
                    "client_name" => self::getOption('ga_plaid_client_name'),
                    "country_codes" => array("GB"),
                    "language" => "en",
                    "user" => [
                        "client_user_id" => self::getOption('ga_plaid_client_user_id')
                    ],
                    "products" => ["auth", "transactions"],
                    "redirect_uri" => "https://fundingalternative.co.uk/obanking-transactions/"
                ];

        if (strtolower(self::getOption('ga_plaid_mode')) != 'sandbox')
        {
            $config['webhook'] = self::$site_admin_url . 'admin-ajax.php?action=plaid_webhook_response';
        }

        return ['config' => $config, 'url' => $url];
    }

    public static function plaidWebhookResponse()
    {
        $time = time();
        $body = file_get_contents('php://input');
        file_put_contents('webhook_response_'.$time.'.txt', $body);
        $webhook = json_decode($body);
    }
}