<html>
<head>
    <title>export links from html tags</title>
</head>
<body>
<form method="post" action="/extract-image-links">
    @csrf
    <textarea name="html" style="width: 800px;height: 400px"></textarea>
    <button style="padding: 10px" type="submit">ثبت</button>
</form>
</body>
</html>
