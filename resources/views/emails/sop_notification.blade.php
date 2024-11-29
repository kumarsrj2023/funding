<p><strong>Hi {{ $data->name }},</strong></p>

<p>We are pleased to provide you with our Funding Alternative Statement of Position. Please ensure the form is completed in full, using accurate and up-to-date information. Kindly note that it must be filled out exclusively by you.</p>

<p>Click the link below to start filling out your form:</p>

<div style="margin-top: 20px;">
    <a href="{{ route('sop.form', $encryptedId) }}" 
       style="padding: 10px 20px; background-color:#1a335f; color:white; text-decoration:none; border-radius:5px; display: inline-block;">
        Start Filling Out the Form
    </a>
</div>
