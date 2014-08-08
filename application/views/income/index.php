<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <div id="weather">

            <h3 class="center_dot"><span>企業年収速報</span></h3>
            <div id="weathers">
                    <?php foreach ($cdatas as $cdata) : ?>

<div id="entry_list">
    <article class="entry">
        <a href="<?php echo '/document/show/'.$cdata->col_code ?>">
        <div class="cat life">生活<span class="cat_arrow"></span></div>
        <div class="title">
            <div class="box_time">
            <time datetime="2014-08-06" class="time"><?php echo strftime($this->lang->line('setting_date_format'), $cdata->col_disclosure); ?><span>(wed)</span></time>
            <span class="writer"><img alt='LIGブログ編集部' src='/images/income/be911a09d48e5e6adbd3b19bfcf6ffee_avatar-30x30_sample.png' class='avatar avatar-30 photo' height='30' width='30' /><?php echo $cdata->col_name; ?></span>
            </div>
            <h1><?php echo $cdata->col_name; ?></h1>
            <p class="sns"><span class="fb">25</span><span class="tw">14</span><span class="hb">4</span></p>
        </div>
        <figure class="figure">
            <p><?php echo $cdata->col_income; ?>万円</p>
        </figure>
        <div class="entry_arrow"><img src="/images/income/icon_arrow_l.png" width="18" height="17" alt=""></div>
        </a>
    </article>
</div>


                    <?php endforeach; ?>
            </div>
        </div>
        <div id="sidebar">
            <img src="/images/ad_example1.gif" alt="csv" />
        </div>
        <span class="cf" />
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
