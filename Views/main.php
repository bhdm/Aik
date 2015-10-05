<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/Resources/js/bootstrap.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/Resources/css/bootstrap.css" type="text/css">
</head>

<body>
<div class="container">
    <table class="table">
        <tr>
            <th>Группа</th>
            <th>Зал</th>
            <th>инструктор</th>
        </tr>
        <?php
        foreach ($data as $i){
            echo '<tr>';
                echo '<td>'.$i->getTitle().'</td>';
                echo '<td>'.$i->getRoom()->getTitle().'</td>';
                echo '<td>'.$i->getInstructor()->getLastName().'</td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>
</body>
</html>