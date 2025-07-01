<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
/* Reset styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: 'Arial', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #f5f9ff;
}

/* Main loader container */
.loader {
    width: 250px;
    height: 250px;
    position: relative;
    animation: rotateLoader 4s cubic-bezier(0.25, 0.8, 0.25, 1) infinite;
    transform-style: preserve-3d;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Blue dot at the center of the loader */
.loader:before {
    content: "";
    width: 30px;
    height: 30px;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    background: #007bff;
    transform: translate(-50%, -50%);
    animation: dotMovement 1.5s infinite ease-in-out;
}

/* SVG circle with rotating path */
.loader svg {
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

/* Circle path animation */
.loader svg circle {
    fill: none;
    stroke: #2F3545;
    stroke-width: 8px;
    stroke-dasharray: 200 60;
    stroke-dashoffset: 0;
    animation: pathCircle 2.5s ease-in-out infinite;
}

/* Keyframe for rotating the loader with 3D effect */
@keyframes rotateLoader {
    0% {
        transform: rotate(0deg);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    50% {
        transform: rotate(180deg);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.25);
    }
    100% {
        transform: rotate(360deg);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
}

/* Keyframes for the circle stroke animation */
@keyframes pathCircle {
    0% {
        stroke-dashoffset: 0;
    }
    100% {
        stroke-dashoffset: 400;
    }
}

/* Keyframe for dot movement */
@keyframes dotMovement {
    0% {
        transform: translate(-50%, -50%) scale(1);
    }
    25% {
        transform: translate(-70%, -50%) scale(1.2);
    }
    50% {
        transform: translate(-50%, -70%) scale(1.4);
    }
    75% {
        transform: translate(-30%, -50%) scale(1.2);
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
    }
}
    </style>
    <title>Dynamic 3D Loader</title>
</head>
<body>
    <div class="loader">
        <svg viewBox="0 0 150 150">
            <circle cx="75" cy="75" r="60"></circle>
        </svg>
    </div>
</body>
</html>
