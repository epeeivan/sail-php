<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=url("assets/css/output.css")?>">
    <link rel="stylesheet" href="<?=url("assets/css/fonts.css")?>">
    <link rel="icon" href="<?=url("assets/images/logo.png")?>">

    <title>Document</title>
</head>
<body class="flex h-screen text-slate-700 flex-wrap">
    <div class=" lg:w-4/12 w-9/12 flex m-auto shadow-lg  bg-slate-50 p-5 space-x-2 rounded">
        <img src="<?=url("assets/images/logo.png")?>" class="h-40"/>
        <div>
            <div>
                <h1 class="font-bold text-sm my-2">welcome to infinite php the most easy php framework</h1>
                <p><?=lang("start_message")?></p>
                <p class="my-2">
                    you can easylly chance this view on
                    <code class="text-slate-500"><span class="text-red-500">app_folder</span>/app/views/home.php</code>
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="" class="bg-slate-200  block p-2 rounded h-min"><?=lang("more")?></a>
                <a href="" class="bg-primary-100 text-white  block p-2 rounded h-min"><?=lang("doc")?></a>
            </div>
        </div>


    </div>
    <div class="w-full mt-auto h-min p-2 flex">
        <div class="mx-auto space-x-5 capitalize">
            <a>Terms use</a>
            <a>licence</a>

        </div>
    </div>
</body>
<style>

    /*body{*/
    /*    display: flex;*/
    /*    height: 100vh;*/
    /*    color:gray;*/
    /*}*/
    /*body>div{*/
    /*    margin: auto;*/
    /*    padding: 10px;*/
    /*    border:1px solid silver;*/
    /*    border-radius: 5px;*/
    /*    background-color:#efefef;*/
    /*}*/
</style>
</html>