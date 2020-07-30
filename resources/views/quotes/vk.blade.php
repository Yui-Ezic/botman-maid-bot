<?php
/** @var $text string text of quotes */
/** @var $author string full name of author */
/** @var $avatar string url of avatar image */
?>

<html>
<head>
    <meta charset="utf-8">

    <title>Vk quote page</title>

    <style>
        /* Chat containers */
        .container {
            background-color: rgba(255, 255, 255, 0);
            font-size: 14px;
            font-family: sans-serif;
            font-weight: 400;
            display: flex;
            flex-direction: row;
            width: 500px;
        }

        .message {
            padding: 10px;
            background-color: #CEE3FF;
            color: black;
            border-radius: 10px;
            max-width: 500px;
            display: table;
            word-break: break-word;
        }

        .message-text {
            margin: 5px 0 5px;
        }

        .message-author {
            font-weight: 700;
            color: rgb(42, 88, 133);
        }

        /* Style images */
        .avatar {
            align-self: flex-start;
            float: left;
            max-width: 50px;
            width: 100%;
            margin-right: 5px;
            border-radius: 50%;
        }

        img.emoji {
            height: 1em;
            width: 1em;
            margin: 0 .05em 0 .1em;
            vertical-align: -0.1em;
        }
    </style>

    <script src="{{ asset('/build/js/twemoji.min.js') }}" crossorigin="anonymous"></script>
</head>

<body>
<div class="container">
    <img class="avatar" src="{{ $avatar }}" alt="Avatar">
    <div class="message">
        <span class="message-author">{{ $author }}</span>
        <p class="message-text">{!! nl2br(e($text)) !!}</p>
    </div>
</div>
<script>
    twemoji.parse(document.body);
</script>
</body>
</html>