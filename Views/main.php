<html>
<head>
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/Resources/js/bootstrap.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/Resources/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="/Resources/css/common.css" type="text/css">
</head>

<body>
<?php
    include_once 'header.php';
?>
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
            <th>ИТОГО</th>
        </tr>
        <?php
        foreach ($groups as $key => $group){
            # Заголовок группы
            echo '<tr>';
            echo '<td class="group-title" id="group-'.$key.'">'.$key,' '.$group->getTitle().'</td>';
            echo '<td class="group-title" colspan="12"></td>';
            echo '<td class="group-title"><a href="#group-'.$key.'">Прикрепить</a></td>';
            echo '</tr>';

            # теперь расчеты

            echo '<tr class="group-'.$key.'">';
            echo '<td>Кол-во человек</td>';
            $userCount = \Model\Driver::userCount($group->getId());
            $tmp1 = 0;
            for($i = 1 ; $i <= 12 ; $i ++ ){
                echo '<td class="text-center">'.$userCount[$i].'</td>';
                $tmp1 += $userCount[$i];
            }
            echo '<td class="text-center">'.$tmp1.'</td>';
            echo '</tr>';


            echo '<tr class="group-'.$key.'">';
            echo '<td>Выручка</td>';
            $userSale = \Model\Driver::userSales($group->getId());
            $tmp2['plan'] = 0;
            $tmp2['fact'] = 0;
            for($i = 1 ; $i <= 12 ; $i ++ ){
                echo '<td class="text-center">'.($userCount[$i]*$group->getPrice()).'<br />'.$userSale[$i].'</td>';
                $tmp2['plan'] += $userCount[$i]*$group->getPrice();
                $tmp2['fact'] += $userSale[$i];
            }
            echo '<td class="text-center">'.$tmp2['plan'].' <br /> '.$tmp2['fact'].'</td>';
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
            echo '<td>Аренда</td>';
            $roomRent = \Model\Driver::roomRent($group->getId());
            $tmp3 = 0;
            for($i = 1 ; $i <= 12 ; $i ++ ){
                echo '<td class="text-center">'.$roomRent[$i].'</td>';
                $tmp3 += $roomRent[$i];
            }
            echo '<td class="text-center">'.$tmp3.'</td>';
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
            echo '<td>Зарплата</td>';
            $userSalary = \Model\Driver::userSalary($group->getId());
            $tmp4['plan'] = 0;
            $tmp4['fact'] = 0;
            for($i = 1 ; $i <= 12 ; $i ++ ){
                echo '<td class="text-center">'.$userSalary[$i]['plan'].' <br /> '.$userSalary[$i]['fact'].'</td>';
                $tmp2['plan'] += $userSalary[$i]['plan'];
                $tmp2['fact'] += $userSalary[$i]['fact'];
            }
            echo '<td class="text-center">'.$tmp4['plan'].' <br /> '.$tmp4['fact'].'</td>';
            echo '</tr>';

            echo '<tr class="group-'.$key.'">';
            echo '<td>Опер. прибыль</td>';
            $tmp5 = 0;
            for($i = 1 ; $i <= 12 ; $i ++ ){
                $s = $userSale[$i] - $roomRent[$i] - $userSalary[$i]['fact'];
                echo '<td class="text-center">'.$s.'</td>';
                $tmp5 += $s;
            }
            echo '<td class="text-center">'.$tmp5.'</td>';
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