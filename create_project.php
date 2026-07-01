<!DOCTYPE html>
<html>
<head>
<title>Face Attendance</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2>Face Attendance Login</h2>

<div class="camera-box">
    <video id="video" autoplay></video>
    <canvas id="overlay"></canvas>
</div>

<button onclick="scanFace()">Scan Face</button>

<script src="js/face-api.min.js"></script>
<script src="js/app.js"></script>

</body>
</html>