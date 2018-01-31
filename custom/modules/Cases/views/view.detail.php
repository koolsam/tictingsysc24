<?php

require_once('include/MVC/View/views/view.detail.php');

class CasesViewDetail extends ViewDetail {
    
    function __construct() {
        parent::__construct();
    }
    
    function display() {

        echo '<script type="text/javascript" src="custom/modules/Cases/javascript/case.js"></script>';
        $issue_faced_by = $this->bean->issue_faced_by_c;
        //$javascript = <<<JS
        ?>
            <script type="text/javascript">
                $(document).ready(function () { 
                    Case.initDetailViewDisplay('<?=$issue_faced_by?>');
                });

            </script>
<?php
// JS;
        parent::display();
        //echo $javascript; //Printing the javascript
    }

}
