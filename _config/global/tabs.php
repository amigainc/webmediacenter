<?php

function SetTabs($tabkey, $tabs)
{
    ?>
<table class="tab" id="<?php echo $tabkey ?>">
    <tr>
        <?php
        $i=0;

        foreach($tabs as $key=>$text)
        {

            if($i==0)
            {
                echo '<td id="tab_'.$key.'" class="tabS">';
            }
            else
            {
                echo '<td id="tab_'.$key.'">';
            }
            echo '<a href="#" onClick="javascript:ChangeTab(\''.$tabkey.'\',\''.$key.'\');">'.$text."</a></td>";
            $i++;
        }
        ?>
    </tr>
</table>
<?php
}