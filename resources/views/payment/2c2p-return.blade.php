<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="0;url={{ $redirectUrl }}">
    <title>กำลังกลับสู่ระบบ...</title>
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, sans-serif;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            margin: 0;
            background: #f5f5f9;
            color: #566a7f;
        }
        .box { text-align: center; padding: 1.5rem; }
        a { color: #696cff; }
    </style>
</head>
<body>
    <div class="box">
        <p>กำลังกลับสู่ระบบ...</p>
        <p><a href="{{ $redirectUrl }}">คลิกที่นี่หากไม่ได้พาไปอัตโนมัติ</a></p>
    </div>
    <script>
        window.location.replace(@json($redirectUrl));
    </script>
</body>
</html>
