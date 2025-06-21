<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BYFROST</title>
  <style>
    body {
      margin: 0;
      background: linear-gradient(135deg, #ffffff, #e0e0e0);
      height: 100vh;
      overflow: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      animation: fadeInCenter 2s ease-out;
    }

    .logo {
      width: 120px;
      opacity: 0;
      transform: scale(0.5);
      animation: logoPop 1.5s ease-out 0.5s forwards;
    }

    h1 {
      opacity: 0;
      font-size: 3.5em;
      color: #333;
      text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
      letter-spacing: 5px;
      animation: textFadeIn 1.5s ease-out 1.5s forwards;
      margin-top: 20px;
    }

    @keyframes logoPop {
      0% {
        opacity: 0;
        transform: scale(0.5);
      }
      60% {
        opacity: 1;
        transform: scale(1.2);
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    @keyframes textFadeIn {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInCenter {
      0% {
        transform: scale(0.95);
        opacity: 0;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <img class="BYFROST" src="Byfrost (6).svg" alt="cd" />
    <h1>BYFROST</h1>
  </div>
</body>
</html>
