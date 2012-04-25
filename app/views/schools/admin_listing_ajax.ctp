<table border="0" cellspacing="0" cellpadding="0" class="listing edit" >
    <tr class="ListHeading">
        <td width="10%" align="center">Sr. No.</th>
        <td width="20%" align="center">Name
            <span id="pagination">
                <?php
                $arrURL = explode("direction:", $_REQUEST['url']);

                $arrSort = explode("sort:", $_REQUEST['url']);
                if ($arrSort[1] != "") {
                    $arrSortSub = explode("/", $arrSort[1]);
                    $arrSortSub = $arrSortSub[0];
                }

                if ($arrSortSub == "name") {
                    if (isset($arrURL['1'])) {
                        if ($arrURL['1'] != "desc")
                            $sortingImage = "<img src='" . $html->url('/img/aroow2.gif') . "' alt='Desc' title='Desc' />";

                        else
                            $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                    }
                }
                else {
                    $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                }
                ?>
                <?php echo $paginator->sort(html_entity_decode($sortingImage), 'name'); ?>
            </span>
            </th>
        <td width="20%" align="center">Attendence
            <span id="pagination">
                <?php
                $arrURL = explode("direction:", $_REQUEST['url']);

                $arrSort = explode("sort:", $_REQUEST['url']);
                if ($arrSort[1] != "") {
                    $arrSortSub = explode("/", $arrSort[1]);
                    $arrSortSub = $arrSortSub[0];
                }

                if ($arrSortSub == "attendence") {
                    if (isset($arrURL['1'])) {
                        if ($arrURL['1'] != "desc")
                            $sortingImage = "<img src='" . $html->url('/img/aroow2.gif') . "' alt='Desc' title='Desc' />";

                        else
                            $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                    }
                }
                else {
                    $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                }
                ?>
                <?php echo $paginator->sort(html_entity_decode($sortingImage), 'attendence'); ?>
            </span>
            </th>

        <td width="20%" align="center">Address
            <span id="pagination">
                <?php
                $arrURL = explode("direction:", $_REQUEST['url']);

                $arrSort = explode("sort:", $_REQUEST['url']);
                if ($arrSort[1] != "") {
                    $arrSortSub = explode("/", $arrSort[1]);
                    $arrSortSub = $arrSortSub[0];
                }

                if ($arrSortSub == "address") {
                    if (isset($arrURL['1'])) {
                        if ($arrURL['1'] != "desc")
                            $sortingImage = "<img src='" . $html->url('/img/aroow2.gif') . "' alt='Desc' title='Desc' />";

                        else
                            $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                    }
                }
                else {
                    $sortingImage = "<img src='" . $html->url('/img/aroow1.gif') . "' alt='Asc' title='Asc' />";
                }
                ?>
                <?php echo $paginator->sort(html_entity_decode($sortingImage), 'address'); ?>
            </span>
            </th>

        <td width="10%" align="center">Edit</th>

        <td width="10%" align="center">Delete</th>
    </tr>
                <?php
                $i = 0;

                foreach ($schools as $school) {

                    if ($page_no)
                        $sr_no = ($page_no * $paginate_limit) - $paginate_limit + 1;
                    else
                        $sr_no = 1;

                    $class = "";
                    if ($i % 2 != 0)
                        $class = 'class="gr-row"';
                    ?>
        <tr <?php e($class); ?> >
            <td align="center"><?php e($i++ + $sr_no); ?>. </td>
            <td align="left"><?php e($school['School']['name']); ?></td>
            <td align="left">
    <?php e($school['School']['attendence']); ?></td>
            <td align="left">
        <?php e($school['School']['address']); ?></td>

            <td align="center"><a href="<?php e($html->url('/admin/schools/school_edit/' . $school['School']['id'])); ?>"><img src="<?php e($html->url('/img/edit-icon.png')); ?>" /></td>

            <td align="center"><a href="<?php e($html->url('/admin/schools/school_delete/' . $school['School']['id'])); ?>" onclick="javascript: return confirm_delete();"><img src="<?php e($html->url('/img/delete.gif')); ?>" /></a></td>
        </tr>
        <?php
    }
    ?>

</table>


    <?php
    if (count($schools) > 0) {
        /* Display paging info */
        ?>
    <div id="pagination">		
        <div class="bottom-text">		
                <?php
                //      echo $paginator->prev();
                //      echo $paginator->numbers(array('separator'=>' - '));
                //      echo $paginator->next();

                echo $paginator->first("<<", array('class' => 'footer_nav'));
                echo '&nbsp;&nbsp;';
                echo $paginator->prev("<", array('class' => 'footer_nav'));
                echo '&nbsp;&nbsp;';
                echo $paginator->numbers(array('separator' => ' | '));
                echo '&nbsp;&nbsp;';
                echo $paginator->next(">", array('class' => 'footer_nav'));
                echo '&nbsp;&nbsp;';
                echo $paginator->last(">>", array('class' => 'footer_nav'));
                ?>
        </div>

    </div>
    <?php
}
?> 