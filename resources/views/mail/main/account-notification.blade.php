<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

</head>
<title>Untitled Document</title>

<body>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f0f0f0;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p {
        margin: 10px 0;
        padding: 0;
        font-weight: normal;
    }

    p {
        font-size: 13px;
    }
</style>

<!-- BODY-->
<div style="max-width: 700px; width: 100%; background: #fff;margin: 30px auto;">

    <div style="padding:30px 60px;">
        <div style="text-align: center;padding: 20px 0;">
            <img src="{{ asset('storage').'/logos/'.$setting->company_logo }}" alt="ST PAUL" width="175" />
        </div>
        @php
            $content    = $template->content;

            $keywords   = ['{customer_name}', '{company_name}', '{company_address}', '{tel_no}', '{mobile_no}'];
            $variables  = [$customer->fullname, $setting->company_name, $setting->company_address, $setting->tel_no, $setting->mobile_no];

            $newContent = str_replace($keywords,$variables,$content);
        @endphp

        {!! $newContent !!}
    </div>
</div>

</body>

</html>
