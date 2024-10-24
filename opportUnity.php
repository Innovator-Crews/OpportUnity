<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        li{
            text-decoration:none;
        }
    </style>
</head>
<body>
    <h1>OpportUnity</h1>
    <div id="container"></div>
    <button onclick="clickme()">Get the list</button>
    
    <script>    
    
    xml_for_redirecting_to_other_form:
        {
            const xhr = new XMLHttpRequest();
            const container = document.getElementById('container');

            function clickme()
            {
                
            xhr.onload = function()
            {
                if(this.status === 200)
                {
                    container.innerHTML = xhr.responseText;
                }
            }
            xhr.open('get', 'xml.html');
            xhr.send();
            }
        }
        
    </script>
</body>
</html>