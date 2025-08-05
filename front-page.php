<?php

/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */
if (!defined('ABSPATH')) exit; ?>
<?php get_header(); ?>

<!-- 無限ループ対応 476×476 正方形スライダー部分 -->
<div class="peek-slider-wrapper">
  <div class="peek-slider">
    <div class="peek-slider-container">
      <?php
      $args = array(
        'post_type'      => 'slide',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
      );
      $slides = new WP_Query($args);
      
      if ($slides->have_posts()) :
        while ($slides->have_posts()) : $slides->the_post();
          $slide_image_id = get_post_meta(get_the_ID(), 'slide_image_id', true);
          $slide_image_url = wp_get_attachment_image_url($slide_image_id, 'full');
          $slide_link = get_post_meta(get_the_ID(), 'slide_link', true);
          
          if (!empty($slide_image_url)) :
          ?>
          <div class="peek-slide">
            <?php if (!empty($slide_link)) : ?>
            <a href="<?php echo esc_url($slide_link); ?>" class="slide-link">
            <?php endif; ?>
              <img src="<?php echo esc_url($slide_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" draggable="false">
              <!-- 吹き出しオーバーレイ -->
              <div class="slide-overlay">
                <div class="read-article">詳細を見る</div>
              </div>
            <?php if (!empty($slide_link)) : ?>
            </a>
            <?php endif; ?>
          </div>
          <?php
          endif;
        endwhile;
        wp_reset_postdata();
      else :
        // デモスライド（管理画面でスライドが設定されていない場合）
        for ($i = 1; $i <= 5; $i++) :
        ?>
        <div class="peek-slide">
          <div class="demo-slide">
            <h2>スライド <?php echo $i; ?></h2>
            <p>管理画面の「スライダー」から<br>476×476pxの正方形画像を<br>アップロードしてください</p>
          </div>
        </div>
        <?php
        endfor;
      endif;
      ?>
    </div>
    
    <!-- ドットとボタンのコントロール部分 -->
    <div class="peek-slider-controls">
      <!-- 前へボタン -->
      <button class="peek-slider-button prev" aria-label="前のスライドへ">❮</button>
      
      <!-- ドットインジケーター -->
      <div class="peek-slider-dots">
        <?php 
        $total_slides = $slides->post_count > 0 ? $slides->post_count : 5;
        for ($i = 0; $i < $total_slides; $i++) : 
        ?>
          <button class="peek-slider-dot<?php echo $i === 0 ? ' active' : ''; ?>" data-index="<?php echo $i; ?>" aria-label="スライド<?php echo $i + 1; ?>へ"></button>
        <?php endfor; ?>
      </div>
      
      <!-- 次へボタン -->
      <button class="peek-slider-button next" aria-label="次のスライドへ">❯</button>
    </div>
  </div>
</div>
<main class="main-content">
<!-- 以下、元のfront-page.phpの内容 -->
        
<!-- 求人検索 -->
<?php get_template_part('search', 'form'); ?>
<h2 class="section-tit">教室一覧から探す</h2>
<script>
// front-page.phpとpage-list.phpのスクリプト部分を修正
$(function(){
    // 地域を選択
    $('.area_btn').click(function(){
        // 現在のスクロール位置を保存
        var scrollPos = $(window).scrollTop();
        
        $('.area_overlay').show();
        $('.pref_area').show();
        var area = $(this).data('area');
        $('[data-list]').hide();
        $('[data-list="' + area + '"]').show();
        
        // スクロール位置を維持
        $(window).scrollTop(scrollPos);
        
        // bodyのスクロールを無効化
        $('body').css({
            'overflow': 'hidden',
            'position': 'fixed',
            'width': '100%',
            'top': -scrollPos
        });
    });
    
    // レイヤーをタップ
    $('.area_overlay').click(function(){
        prefReset();
    });
    
    // 都道府県をクリック
    $('.pref_list [data-id]').click(function(){
        if($(this).data('id')){
            var id = $(this).data('id');
            window.location.href = '/list/?pref=' + id;
        }
    });

    // テーブル外をクリックしたら都道府県選択を閉じる
    $(document).on('click', function(event) {
        if (!$(event.target).closest('.pref_area').length && !$(event.target).closest('.area_btn').length) {
            prefReset();
        }
    });
    
    // リストページでURLパラメータから都道府県を表示（修正版）
    $(document).ready(function() {
        var urlParams = new URLSearchParams(window.location.search);
        var prefId = urlParams.get('pref');
        
        if (prefId && $('.tab-ctt').length > 0) {
            $('.tab-ctt [data-id]').hide();
            $('.tab-ctt [data-id="' + prefId + '"]').show();
            
            // スクロール処理を遅延実行
            setTimeout(function() {
                var $target = $('.tab-ctt [data-id="' + prefId + '"]');
                if ($target.length > 0 && $target.offset()) {
                    var targetOffset = $target.offset().top - 250;
                    
                    // スムーズスクロールを無効化してジャンプ
                    $('html, body').scrollTop(targetOffset);
                }
            }, 100); // 500msから100msに短縮
        }
    });
});

function prefReset() {
    // bodyのスクロール無効化を解除
    var scrollPos = -parseInt($('body').css('top'));
    $('body').css({
        'overflow': '',
        'position': '',
        'width': '',
        'top': ''
    });
    $(window).scrollTop(scrollPos);
    
    $('.pref_area').hide();
    $('.area_overlay').hide();
}
</script>

		
<!--
		<?php
$post_id ='11601'; // 参照したい投稿のID
$post = get_post($post_id); // 投稿を取得

if ($post) {
    echo apply_filters('the_content', $post->post_content); // 投稿のコンテンツを出力
}
?>
-->
<div class="japan_map">
		<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/map.svg">
    <span class="area_btn area1" data-area="1">北海道・東北</span>
    <span class="area_btn area2" data-area="2">関東</span>
    <span class="area_btn area3" data-area="3">中部</span>
    <span class="area_btn area4" data-area="4">近畿</span>
    <span class="area_btn area5" data-area="5">中国・四国</span>
    <span class="area_btn area6" data-area="6">九州・沖縄</span>
    
    <div class="area_overlay"></div>
    <div class="pref_area">
        <div class="pref_list" data-list="1">
            <div data-id="1">北海道</div>
            <div data-id="2">青森県</div>
            <div data-id="3">岩手県</div>
            <div data-id="4">宮城県</div>
            <div data-id="5">秋田県</div>
            <div data-id="6">山形県</div>
            <div data-id="7">福島県</div>
            <div></div>
        </div>
        
        <div class="pref_list" data-list="2">
            <div data-id="8">茨城県</div>
            <div data-id="9">栃木県</div>
            <div data-id="10">群馬県</div>
            <div data-id="11">埼玉県</div>
            <div data-id="12">千葉県</div>
            <div data-id="13">東京都</div>
            <div data-id="14">神奈川県</div>
            <div></div>
        </div>
        
        <div class="pref_list" data-list="3">
            <div data-id="15">新潟県</div>
            <div data-id="16">富山県</div>
            <div data-id="17">石川県</div>
            <div data-id="18">福井県</div>
            <div data-id="19">山梨県</div>
            <div data-id="20">長野県</div>
            <div data-id="21">岐阜県</div>
            <div data-id="22">静岡県</div>
            <div data-id="23">愛知県</div>
            <div></div>
        </div>
        
        <div class="pref_list" data-list="4">
            <div data-id="24">三重県</div>
            <div data-id="25">滋賀県</div>
            <div data-id="26">京都府</div>
            <div data-id="27">大阪府</div>
            <div data-id="28">兵庫県</div>
            <div data-id="29">奈良県</div>
            <div data-id="30">和歌山県</div>
            <div></div>
        </div>
        
        <div class="pref_list" data-list="5">
            <div data-id="31">鳥取県</div>
            <div data-id="32">島根県</div>
            <div data-id="33">岡山県</div>
            <div data-id="34">広島県</div>
            <div data-id="35">山口県</div>
            <div data-id="36">徳島県</div>
            <div data-id="37">香川県</div>
            <div data-id="38">愛媛県</div>
            <div data-id="39">高知県</div>
            <div></div>
        </div>
        
        <div class="pref_list" data-list="6">
            <div data-id="40">福岡県</div>
            <div data-id="41">佐賀県</div>
            <div data-id="42">長崎県</div>
            <div data-id="43">熊本県</div>
            <div data-id="44">大分県</div>
            <div data-id="45">宮崎県</div>
            <div data-id="46">鹿児島県</div>
            <div data-id="47">沖縄県</div>
        </div>
    </div>
</div>
<br>

      <div class="tab-wrap">
       <!-- 北海道・東北 エリア -->
        <div class="tab-ctt tab--show">
			<div data-id="4" style="text-align: center; display: none; font-weight: bold;">
       ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="6" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="15" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="16" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="18" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="19" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="21" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="22" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="24" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="25" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="26" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="29" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="30" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="31" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="32" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="33" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="35" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="36" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="37" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="39" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>
<div data-id="42" style="text-align: center; display: none; font-weight: bold;">
    ※ご指定結果<br>現在ご指定の都道府県に教室がございません。
</div>



<!-- 職種から探す -->
<section class="job-category">
  <h2 class="section-tit">職種から探す</h2>
  <div class="category-container">
    <?php
    // 職種のスラッグとリンク先を配列で定義
    $positions = array(
      '児童発達支援管理責任者' => 'jidou-kanrisha',
      '児童指導員' => 'jidou-shidouin',
      '保育士' => 'hoikushi',
      '理学療法士' => 'pt',
      '作業療法士' => 'ot',
      '言語聴覚士' => 'st',
      'その他' => 'other'
    );
    
    // 職種のアイコンクラスを定義
    $position_icons = array(
      '児童発達支援管理責任者' => 'fas fa-user-shield',
      '児童指導員' => 'fas fa-users',
      '保育士' => 'fas fa-baby-carriage',
      '理学療法士' => 'fas fa-running',
      '作業療法士' => 'fas fa-heart',
      '言語聴覚士' => 'fas fa-comment-dots',
      'その他' => 'fas fa-ellipsis-h'
    );
    
    // 各職種のリンクを生成
    foreach ($positions as $position_name => $position_slug) {
      $icon_class = isset($position_icons[$position_name]) ? $position_icons[$position_name] : 'fas fa-briefcase';
      ?>
      <a href="<?php echo home_url('/jobs/position/' . $position_slug . '/'); ?>" class="category-item">
        <h3><?php echo esc_html($position_name); ?></h3>
        <div class="category-icon">
          <i class="<?php echo esc_attr($icon_class); ?>"></i>
        </div>
      </a>
      <?php
    }
    ?>
  </div>
</section>
<!-- 特徴から探す -->
<section class="feature-search">
  <h2 class="section-tit">特徴から探す</h2>
  <div class="tokuchou-container">
    <?php
    // 特徴のデータ（名前、スラッグ、画像ファイル名）
    $features = array(
      array(
        'name' => '未経験歓迎の求人',
        'slug' => 'mikeiken',
        'image' => 'mikeikenn.webp'
      ),
      array(
        'name' => 'オープニングスタッフの求人',
        'slug' => 'openingstaff',
        'image' => 'opening-staff.webp'
      ),
      array(
        'name' => '高収入の求人',
        'slug' => 'koushuunixyuu',
        'image' => 'high-income.webp'
      )
    );
    
    // 各特徴のリンクを生成
    foreach ($features as $feature) {
      ?>
      <a href="<?php echo home_url('/jobs/feature/' . $feature['slug'] . '/'); ?>" class="tokuchou-item">
        <div class="tokuchou-image">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/<?php echo esc_attr($feature['image']); ?>" alt="<?php echo esc_attr($feature['name']); ?>">
          <div class="tokuchou-title">
            <h3><?php echo esc_html($feature['name']); ?></h3>
          </div>
        </div>
      </a>
      <?php
    }
    ?>
  </div>
</section>


<!-- 新着求人情報（最終版） -->
<section class="new-jobs">
  <div class="container">
    <h2 class="section-tit">新着求人情報</h2>
    <div class="job-slider-wrapper">
      
      <div class="job-container">
        <?php
        // テキストの長さを制限する関数
        function limit_text_job($text, $limit = 30) {
          if (mb_strlen($text) > $limit) {
            return mb_substr($text, 0, $limit) . '...';
          }
          return $text;
        }
        
        // 求人投稿を取得するクエリ（PC/スマホ共通で15件取得）
        $job_args = array(
          'post_type' => 'job',
          'posts_per_page' => 15,
          'orderby' => 'date',
          'order' => 'DESC'
        );
        
        $job_query = new WP_Query($job_args);
        
        // 求人が見つかった場合
        if ($job_query->have_posts()) :
          $card_count = 0;
          
          while ($job_query->have_posts()) : $job_query->the_post();
            $card_count++;
            
            // モバイル用のクラスを追加（6件目以降は非表示）
            $mobile_hide_class = ($card_count > 5) ? ' mobile-hide' : '';
            
            // 求人情報を取得（既存のコードをそのまま使用）
            $facility_name = get_post_meta(get_the_ID(), 'facility_name', true);
            $facility_company = get_post_meta(get_the_ID(), 'facility_company', true);
            $facility_address = get_post_meta(get_the_ID(), 'facility_address', true);
            $salary_range = get_post_meta(get_the_ID(), 'salary_range', true);
            $facility_address = preg_replace('/〒\d{3}-\d{4}\s*/', '', $facility_address);

            // テキストの長さを制限
            $facility_name = limit_text_job($facility_name, 20);
            $facility_company = limit_text_job($facility_company, 20);
            $facility_address = limit_text_job($facility_address, 30);
            $salary_range = limit_text_job($salary_range, 30);
            
            // タクソノミーから職種と雇用形態を取得
            $position_display_text = get_job_position_display_text(get_the_ID());
$position_name = !empty($position_display_text) ? limit_text_job($position_display_text, 20) : '';
            
            $job_type = wp_get_object_terms(get_the_ID(), 'job_type', array('fields' => 'names'));
            $type_name = !empty($job_type) ? $job_type[0] : '';
            
            // 特徴タグを取得
            $job_features = wp_get_object_terms(get_the_ID(), 'job_feature', array('fields' => 'names'));
            
            // サムネイル画像URL
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            if (!$thumbnail_url) {
              $thumbnail_url = get_stylesheet_directory_uri() . '/images/job-image-default.jpg';
            }
            
            // 雇用形態タグのクラス決定
            $type_class = 'other';
            if ($type_name == '正社員') {
              $type_class = 'full-time';
            } elseif (strpos($type_name, 'パート') !== false || strpos($type_name, 'アルバイト') !== false) {
              $type_class = 'part-time';
            }
        ?>
        <!-- 求人カード -->
        <div class="jo-card<?php echo $mobile_hide_class; ?>">
          <div class="jo-header">
            <div class="cmpany-name">
              <p class="bold-text"><?php echo esc_html($facility_name); ?></p>
              <p><?php echo esc_html($facility_company); ?></p>
            </div>
            <div class="employment-type <?php echo $type_class; ?>">
              <?php echo esc_html($type_name); ?>
            </div>
          </div>
          <div class="jo-image">
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="求人画像">
          </div>
          <div class="jo-info">
            <h3 class="jo-title"><?php echo esc_html($position_name); ?></h3>
            <div class="inf-item">
              <span class="inf-icon"><i class="fa-solid fa-location-dot"></i></span>
              <p class="job-location"><?php echo esc_html($facility_address); ?></p>
            </div>
            <div class="inf-item">
              <span class="inf-icon"><i class="fa-solid fa-money-bill-wave"></i></span>
              <p class="job-sala">
                <?php 
                $salary_type = get_post_meta(get_the_ID(), 'salary_type', true);
                $salary_type_text = ($salary_type == 'HOUR') ? '時給 ' : '月給 ';
                echo esc_html($salary_type_text . $salary_range);
                if (strpos($salary_range, '円') === false) {
                  echo '円';
                }
                ?>
              </p>
            </div>
            <div class="job-tags">
              <?php if (!empty($job_features)) : 
                $count = 0;
                foreach ($job_features as $feature) : 
                  if ($count < 3) :
                    $feature = limit_text_job($feature, 15);
              ?>
                <span class="feature-tag"><?php echo esc_html($feature); ?></span>
              <?php 
                  endif;
                  $count++;
                endforeach; 
              endif; 
              ?>
            </div>
          </div>
          <div class="job-footer">
            <a href="<?php the_permalink(); ?>" class="detail-btn">詳細を見る <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <?php
          endwhile;
          wp_reset_postdata();
        else :
        ?>
        <p>現在、求人情報はありません。</p>
        <?php endif; ?>
      </div>
      
      <!-- スライドインジケーター -->
      <div class="slide-indicators">
        <?php 
        if ($card_count > 0) {
          // PC用インジケーター（15件÷5件/画面 = 3個）
          $pc_slides = ceil($card_count / 5);
          
          // モバイル用インジケーター（5件÷1件/画面 = 5個）
          $mobile_slides = min($card_count, 5);
        ?>
          <!-- PC用インジケーター -->
          <div class="indicators-pc">
            <?php for ($i = 0; $i < $pc_slides; $i++) : ?>
              <div class="indicator<?php echo ($i == 0) ? ' active' : ''; ?>" data-slide="<?php echo $i; ?>"></div>
            <?php endfor; ?>
          </div>
          
          <!-- モバイル用インジケーター -->
          <div class="indicators-mobile">
            <?php for ($i = 0; $i < $mobile_slides; $i++) : ?>
              <div class="indicator<?php echo ($i == 0) ? ' active' : ''; ?>" data-slide="<?php echo $i; ?>"></div>
            <?php endfor; ?>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</section>

<!-- サイト案内 -->
<section class="about-site">
  <div class="about-container">
    <h2 class="about-main-title">こどもプラス求人サイトへようこそ！<br>あなたに最適な職場が見つかる場所。</h2>
    
    <div class="about-items">
      <div class="about-item">
        <h3 class="about-item-title">他にはない充実した求人情報</h3>
        <div class="about-item-image">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/feature-unique.webp" alt="充実した求人情報">
        </div>
        <p class="about-item-text">一般的な給与・勤務時間の情報だけでなく、実際に働くスタッフの生の声や職場の雰囲気まで、リアルな情報をお届けします。「どんな職場なのか」が具体的にイメージできる求人情報を提供しています。</p>
      </div>
      
      <div class="about-item">
        <h3 class="about-item-title">スムーズな応募プロセス</h3>
        <div class="about-item-image">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/feature-process.webp" alt="スムーズな応募プロセス">
        </div>
        <p class="about-item-text">会員登録が完了すると、応募フォームに情報が自動入力されます。そのため、面倒な手続きなしで、効率良く求人への応募が可能です。</p>
      </div>
      
      <div class="about-item">
        <h3 class="about-item-title">あなたにぴったりの求人をお届け</h3>
        <div class="about-item-image">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/feature-matching.webp" alt="ぴったりの求人">
        </div>
        <p class="about-item-text">ご登録いただいた希望条件に合わせて、あなたにマッチした求人情報をお知らせします。また、最新の求人情報もいち早くチェックできるので、理想の職場との出会いを逃しません。</p>
      </div>
    </div>
  </div>
</section>
<div class="banner-container">
        <a href="/contact/" class="banner-link">
            <!-- PC用画像 -->
            <img src="http://testjc-fc.kphd-portal.net/wp-content/uploads/2025/06/ご相談窓口.png" 
                 alt="PC用バナー画像" 
                 class="banner-image banner-pc">
            
            <!-- スマホ用画像 -->
            <img src="http://testjc-fc.kphd-portal.net/wp-content/uploads/2025/06/相談窓口バナーSP.png" 
                 alt="モバイル用バナー画像" 
                 class="banner-image banner-mobile">
        </a>
    </div>

<!-- マッチング案内 -->
<section class="matching-section">
  <div class="matching-container">
    <h2 class="matching-title">あなたにぴったりの<br>求人情報を見てみよう</h2>
    <p class="matching-desc">あなたのスキルや経験、希望に合った求人情報を閲覧できます。<br>会員登録をして、簡単に応募を行うましょう。</p>
    <div class="matching-image">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/matching-puzzle.webp" alt="マッチング">
    </div>
    <div class="matching-label">matching</div>
    <a href="<?php echo is_user_logged_in() ? '/members/' : '/register/'; ?>" class="register-large-btn">
      <span class="btn-icon">▶</span>登録して情報を見る
    </a>
  </div>
</section>
<!-- トップページ用スマホ固定フッターバー -->
<div class="home-fixed-footer-bar">
    <div class="home-fixed-footer-container">
        <?php if (is_user_logged_in()) : ?>
            <!-- ログイン中ユーザー向けメニュー -->
            <a href="<?php echo home_url('/members/'); ?>" class="home-footer-button">
                <i class="fas fa-user"></i>
                <span>マイページ</span>
            </a>
            <a href="<?php echo home_url('/favorites/'); ?>" class="home-footer-button">
                <i class="fas fa-star"></i>
                <span>お気に入り</span>
            </a>
        <?php else : ?>
            <!-- 未ログインユーザー向けメニュー -->
            <a href="<?php echo home_url('/register/'); ?>" class="home-footer-button">
                <i class="fas fa-user-plus"></i>
                <span>会員登録</span>
            </a>
            <a href="<?php echo home_url('/login/'); ?>" class="home-footer-button">
                <i class="fas fa-sign-in-alt"></i>
                <span>ログイン</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<style>
/* モバイルで6件目以降を非表示 */
@media (max-width: 768px) {
  .jo-card.mobile-hide {
    display: none !important;
  }
}

/* インジケーターの表示制御 */
.indicators-pc {
  display: flex;
  justify-content: center;
  gap: 8px;
}

.indicators-mobile {
  display: none;
  justify-content: center;
  gap: 6px;
}

@media (max-width: 768px) {
  .indicators-pc {
    display: none;
  }

  .indicators-mobile {
    display: flex;
  }
}

/* インジケーターのスタイル */
.indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator.active {
  background-color: #26b7a0;
  transform: scale(1.2);
}

@media (max-width: 768px) {
  .indicator {
    width: 8px;
    height: 8px;
  }
}
/* 新着求人情報のインジケーター修正 */

/* スライドインジケーターの基本スタイル強化 */
.slide-indicators {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 20px;
  padding: 10px 0;
}

.indicator {
  width: 12px !important;
  height: 12px !important;
  border-radius: 50% !important;
  background-color: #ddd !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
  border: none !important;
  outline: none !important;
  display: block !important;
}

/* アクティブなインジケーター（緑色）- 優先度を最高に */
.slide-indicators .indicator.active,
.indicators-pc .indicator.active,
.indicators-mobile .indicator.active {
  background-color: #26b7a0 !important;
  transform: scale(1.2) !important;
  box-shadow: 0 2px 8px rgba(38, 183, 160, 0.4) !important;
}

.indicator:hover {
  background-color: #bbb !important;
  transform: scale(1.1) !important;
}

/* PC用インジケーター */
.indicators-pc {
  display: flex !important;
  justify-content: center !important;
  gap: 8px !important;
}

.indicators-mobile {
  display: none !important;
  justify-content: center !important;
  gap: 6px !important;
}

/* モバイル対応 */
@media (max-width: 768px) {
  .indicators-pc {
    display: none !important;
  }

  .indicators-mobile {
    display: flex !important;
  }
  
  .indicator {
    width: 10px !important;
    height: 10px !important;
  }

}

/* 既存のスタイルを上書き */
.new-jobs .indicator {
  width: 12px !important;
  height: 12px !important;
  border-radius: 50% !important;
  background-color: #ddd !important;
  cursor: pointer !important;
  transition: all 0.3s ease !important;
}

.new-jobs .indicator.active {
  background-color: #26b7a0 !important;
  transform: scale(1.2) !important;
}
	

</style>
	<script>
document.addEventListener('DOMContentLoaded', function() {
    // WordPressのログイン状態をチェック
    var isLoggedIn = document.body.classList.contains('logged-in');
    
    // スライダーのリンクを取得
    var sliderLinks = document.querySelectorAll('.slider-link'); // スライダーのaタグにこのクラスを付与
    
    sliderLinks.forEach(function(link) {
        var originalHref = link.getAttribute('href');
        
        if (isLoggedIn) {
            // ログイン済み：/members/ に変更
            link.setAttribute('href', '/members/');
        } else {
            // 未ログイン：/register/ に変更  
            link.setAttribute('href', '/register/');
        }
    });
});
</script>
<?php get_footer(); ?>