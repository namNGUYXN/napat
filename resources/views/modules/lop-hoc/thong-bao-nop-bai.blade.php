<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kết quả nộp bài</title>
    <meta http-equiv="refresh" content="5;url={{ route('lop-hoc.detail', ['slug' => $lop->slug]) }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #333;
        }
        .message {
            border: 1px solid #ccc;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="message">
        @if($thanhCong)
            <h2 class="success">Nộp bài thành công!</h2>
            <p>Bạn đã trả lời đúng {{ $soCauDung }} câu.</p>
        @else
            <h2 class="error">Nộp bài thất bại!</h2>
            <p>{{ $thongBao }}</p>
        @endif

        <p>Trang sẽ chuyển về lớp học sau 5 giây...</p>
    </div>
</body>
</html>
