<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <div id="boxLeisureDetail">
            <h3>ハレコの予測について</h3>
            <p>
                現在の予測的中率は<strong><?php echo $odds->percentage; ?>%</strong>です。<br /><br />
                
                <strong>過去50年の過去データをベースに</strong><br />
                ハレコは過去50年の過去データを元に、独自の天気予測エンジンを開発して未来の天気を予測しています。<br />
                1周間以上先の天気の予測正答確率は50%が限界と言われ、<br />
                現代の天気予報と同程度の性能数値を叩き出すことはほぼ不可能です。<br />
                
                <strong>的中率6割を目指して</strong><br />
                それでも6割を超す正答率を目指して天気予測エンジンを開発しました。<br />
                正答確率をオープンにすることによって、ハレコの透明性を高めつつ、<br />
                予想エンジンの性能向上を目指していきます。
            </p><br />

            <h3>ハレコの注意事項</h3>
            <p>
                <strong>ハレコで予測している天気はあくまでも統計的な予想であり、気象情報を元にした予報ではありません。</strong><br />
                <strong>ハレコで使用している過去のデータは気象庁のデータを元にしています。</strong><br />
                <strong>天気の予想が外れても責任をとるものではありません。指標の一つとしご使用ください。</strong><br />
            </p><br />

            <h3>開発の経緯</h3>
            <p>
                <strong>自分の結婚式をどうしても晴れの日にしたかったから</strong><br />
                昔から天気の予測可能範囲に疑問を持っていました。<br /><br />
                
                旅行が好きで遠くに行く計画を立てる度に天気が気になってしょうがなかった。<br />
                旅行の1周間前には天気予報を見て一喜一憂していました。<br /><br />
                
                気象情報は刻一刻と変化するものだし、<br />
                未来の天気予報を出せない事情はよくわかっていました。<br /><br />
                
                しかしここである疑問が湧きました。<br />
                気象情報を元にしないで統計的なデータマイニングで天気を予想するとどうなるのだろうか？<br /><br />
                
                全く的中しないのか？<br />
                どれぐらいの確率になるのか？<br /><br />
                
                統計的な予想で晴れる確率60%程度でも当てることができれば、十分な数字ではないのかと考えました。<br /><br />
            </p>

            <h3>展望</h3>
            <p>
                来年にかけて日本だけでなく、世界の天気予測を始めるつもりです。<br />
                海外への旅行に行くときに、概算でも良いので晴れるのかどうかは個人的にも知りたい。
            </p>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
