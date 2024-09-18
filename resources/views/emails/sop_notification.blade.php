<p style=""><strong>Hi</strong></p><br>
<span>{{ $data->name }}</span><br>
<span>This is a system-generated message. Please click the link below</span>
<br><br><br>
<div style="">
    <a href="{{ route('sop.form', $encryptedId) }}" style="padding: 10px 20px; background-color:#1a335f; color:white; display: inline-block">Generate SOP</a>
</div>