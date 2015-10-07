<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/Resources/js/bootstrap.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/Resources/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/Resources/css/common.css" type="text/css">
</head>

<body>
<div class="container">
    <table class="table">
        <tr>
<!--            <th></th>-->
            <th>Группа</th>
<!--            <th>Зал</th>-->
<!--            <th>инструктор</th>-->
            <?php
            for($i = 1 ; $i <= 12 ; $i ++ ){
                echo '<th>'.getMonthShortName($i).'</th>';
            }
            ?>
        </tr>
        <?php
        foreach ($groups as $key => $group){
            # Заголовок группы
            echo '<tr>';
                echo '<td class="group-title" id="group-'.$key.'">'.$key,' '.$group->getTitle().'</td>';
                echo '<td class="group-title" colspan="12"></td>';
            echo '</tr>';

            # теперь расчеты

            echo '<tr class="group-'.$key.'">';
                echo '<td>Кол-во человек</td>';
                $userCount = \Model\Driver::userCount($group->getId());
                for($i = 1 ; $i <= 12 ; $i ++ ){
                    echo '<td class="text-center">'.$userCount[$i].'</td>';
                }
            echo '</tr>';


            echo '<tr class="group-'.$key.'">';
                echo '<td>Выручка</td>';
                $userSale = \Model\Driver::userSales($group->getId());
                for($i = 1 ; $i <= 12 ; $i ++ ){
                    echo '<td class="text-center">'.($userCount[$i]*$group->getPrice()).'/'.$userSale[$i].'</td>';
                }
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
                echo '<td>Аренда</td>';
                        $roomRent = \Model\Driver::roomRent($group->getId());
                        for($i = 1 ; $i <= 12 ; $i ++ ){
                            echo '<td class="text-center">'.$roomRent[$i].'</td>';
                        }
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
                echo '<td>Зарплата</td>';
                        $userSalary = \Model\Driver::userSalary($group->getId());
                        for($i = 1 ; $i <= 12 ; $i ++ ){
                            echo '<td class="text-center">'.$userSalary[$i]['plan'].'/'.$userSalary[$i]['fact'].'</td>';
                        }
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
                echo '<td>Опер. прибыль</td>';
                for($i = 1 ; $i <= 12 ; $i ++ ){
                    echo '<td class="text-center">'.($userSale[$i] - $roomRent[$i] - $userSalary[$i]['fact']).'</td>';
                }
            echo '</tr>';
        }
        ?>
    </table>
</div>
<script>
    $(document).ready(function(){
       $('.group-title').click(function(){
           if ($('.'+$(this).attr('id')).css('display') == 'none'){
               $('.'+$(this).attr('id')).css('display', 'table-row');
           } else{
               $('.'+$(this).attr('id')).fadeOut();
           }
       })
    });
</script>
</body>
</html>