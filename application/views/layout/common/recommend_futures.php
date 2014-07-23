    <div class="guide">
        <h2><?php echo $recommend_futures_title; ?></h2>
        <?php echo form_open('/json/futures','method="post" id="futures_search" class="futures_search"'); ?>
        <div id="btnPc">
            <dl class="btnPc01 cf gray">
                <dt>日付：</dt>
                <dd id="future_searchBox"><input type="text" name="date" value="日付を選択" id="datepicker" /></dd>
                <dd><?php echo date("Y年n月j日",strtotime("+8 day")); ?>より前の日付は、<br />気象情報を元にした各所の天気予報をご確認ください。</dd>
            </dl>

            <dl class="btnPc02 radio-group cf">
                <dt>天気：</dt>
                <dd><input type="radio" id="weather9" name="weather" value="9" /><label for="weather9">指定なし</label></dd>
                <dd><input type="radio" id="weather0" name="weather" value="0" checked /><label for="weather0">晴</label></dd>
                <dd><input type="radio" id="weather1" name="weather" value="1" /><label for="weather1">雨</label></dd>
                <dd><input type="radio" id="weather2" name="weather" value="2" /><label for="weather2">曇</label></dd>
                <dd><input type="radio" id="weather3" name="weather" value="3" /><label for="weather3">雷</label></dd>
                <dd><input type="radio" id="weather4" name="weather" value="4" /><label for="weather4">雪</label></dd>
            </dl>
            <dl class="btnPc03 radio-group cf">
                <dt>晴数：</dt>
                <dd><input type="radio" id="sequence1" name="daytime_shine_sequence" value="1" checked /><label for="sequence1">指定なし</label></dd>
                <dd><input type="radio" id="sequence2" name="daytime_shine_sequence" value="2" /><label for="sequence2">2日連続</label></dd>
                <dd><input type="radio" id="sequence3" name="daytime_shine_sequence" value="3" /><label for="sequence3">3日連続</label></dd>
                <dd><input type="radio" id="sequence4" name="daytime_shine_sequence" value="4" /><label for="sequence4">4日連続</label></dd>
            </dl>
            <dl class="btnPc04 check-group cf gray">
                <dt>曜日：</dt>
                <dd><input type="checkbox" id="day_type0" name="dummy[]" value="0" /><label for="day_type0">指定なし</label></dd>
                <dd><input type="checkbox" id="day_type1" name="dummy[]" value="1" /><label for="day_type1">月</label></dd>
                <dd><input type="checkbox" id="day_type2" name="dummy[]" value="2" /><label for="day_type2">火</label></dd>
                <dd><input type="checkbox" id="day_type3" name="dummy[]" value="3" /><label for="day_type3">水</label></dd>
                <dd><input type="checkbox" id="day_type4" name="dummy[]" value="4" /><label for="day_type4">木</label></dd>
                <dd><input type="checkbox" id="day_type5" name="dummy[]" value="5" /><label for="day_type5">金</label></dd>
                <dd><input type="checkbox" id="day_type6" name="dummy[]" value="6" checked="check" /><label for="day_type6">土</label></dd>
                <dd><input type="checkbox" id="day_type7" name="dummy[]" value="7" checked="check"/><label for="day_type7">日</label></dd>
                <dd><input type="checkbox" id="day_type8" name="dummy[]" value="8" checked="check" /><label for="day_type8">祝日</label></dd>
            </dl>
            <dl class="btnPc05 cf">
            <dd>例）土曜日、日曜日の両日とも晴れる日を探したい。　→　[2日連続]ボタンON　+　[土][日]ボタンON</dd>
            </dl>
        </div>
        
        <div id="btnSp">
            <div class="navSp">
                <span><a href="/search/weather/<?php echo $search_type.'/'.$search_object_id.'/'.(isset($jalan_h_id) ? $jalan_h_id : 0).(isset($search_keyword) && $search_keyword != '' ? '/'.urlencode($search_keyword) : ''); ?>">▼検索条件を変更する</a></span>
            </div>
        </div>
        
        <input type="hidden" name="sp" id="sp" value="1" />
        <input type="hidden" name="page" id="page" value="1" />
        <input type="hidden" name="type" value="area" />
        <input type="hidden" name="day_type" id="day_type" value="" />
        <input type="hidden" name="search_type" value="<?php echo $search_type; ?>" />
        <input type="hidden" name="search_object_id" value="<?php echo $search_object_id; ?>" />
        <input type="hidden" name="search_keyword" value="<?php echo isset($search_keyword) ? $search_keyword : ''; ?>" />
        <input type="hidden" name="h_id" value="<?php echo isset($jalan_h_id) ? $jalan_h_id : 0; ?>" />
        <input type="hidden" name="area_id" value="<?php echo $area_id; ?>" />
        </form>

    <script src="/js/lib/picker.js"></script>
    <script src="/js/lib/picker.date.js"></script>
    <script src="/js/lib/picker.time.js"></script>
    <script src="/js/lib/legacy.js"></script>
    <script src="/js/lib/lang-ja.js"></script>
    <script src="/js/lib/app.js"></script>
<?php $this->load->view('layout/parts/recommend_boxes'); ?>
    </div>