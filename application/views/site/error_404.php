<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <!-- ■ MAIN CONTENTS ■ -->
        <div id="page_w">
            <div class="c_wrapper_w">
                <h3 class="l1">404 File not found.</h3>
                    <table style="width:100%;height:400px;">
                        <tr>
                            <td align="center">
                            <img src="/images/404.png" />
                            </td>
                        </tr>
                    </table>

            </div>
            <!--/c_wrapper-->
        </div>
        <!--/page-->
        
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
