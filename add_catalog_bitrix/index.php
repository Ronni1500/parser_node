<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Title</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="prerol сol">
            <div class="alert alert-danger" role="alert">Наполнение каталога <div class="result-total"></div></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <h1>Наполнение каталога</h1>
            <p><button name="start">Начать</button></p>
        </div>        
    </div>
    <div class="row">
        <div class="col page-body">
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
var id_el = 0;
    $(document).ready(function(){        
        $(document).on('click', '[name=start]', function(){
                $('.page-body').prepend('<div class="alert alert-danger" role="alert">Процесс запущен</div>').slideDown("slow");
                    var request = function(id){
                        if(id === false) return;
                        $.ajax({
                            url: 'add_complex_section.php',
                            type: 'POST',
                            data: {id: id},
                            dataType: 'json',
                            // dataType: 'html',
                            success: function (data) {
                                $('.page-body').prepend('<div class="alert alert-success" role="alert">"Добавлен "'+data.message+'</div>').slideDown("slow");
                                $('.result-total').html('Добавлено ' + data.id + ' из ' + data.count);
                                console.log('answer:', data);	
                                request(data.id);
                            },
                            error: function (data) {
                                console.log('form error:', data);	
                                $('.page-body').prepend('<div class="alert alert-danger" role="alert">Ошибка'+data.responseText+'</div>').slideDown("slow");		
                            }
                        });
                    }
                    //Начальный запрос
                    request(0);
            });
    });
</script>
</body>
</html>