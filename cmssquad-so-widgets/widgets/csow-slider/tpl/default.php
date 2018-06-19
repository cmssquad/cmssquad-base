<?php
/**
 * @var array $slides
 * @var string $type
 * @var boolean $show_nav
 * @var boolean $autoplay
 * @var int $timeout
 * @var boolean $pause_hover
 */
?>
<div class="csow-slider-wrapper">

    <?php if($type == "fullscreen"): ?>
    <div class="csow-slider-outer-wrapper">
        <div class="csow-slider-inner-wrapper">
    <?php endif; ?>
            <div class="csow-slider <?php echo "slider-$type"; ?>"
                 data-timeout="<?php echo $timeout; ?>"
                 data-show-nav="<?php echo $show_nav; ?>"
                 data-type="<?php echo $type; ?>"
                 data-autoplay="<?php echo $autoplay; ?>"
                 data-pause-hover="<?php echo $pause_hover; ?>">
                <div class="csow-slider-items">
                <?php foreach($slides as $slide): ?>
                    <div class="csow-slider-item">


                        <?php if($type == "fullscreen"): ?>
                        <div class="csow-slider-fullscreen-image"
                             style="background-image: url(<?php echo $slide['src'][0] ?>);"
                             aria-label="<?php echo $slide['alt_text']; ?>"></div>
                        <div class="container">
                            <div class="csow-slide-caption"><?php echo $slide['text']; ?></div>
                        </div>
                        <?php else: ?>
                            <img src="<?php echo $slide['src'][0]; ?>" alt="<?php echo $slide['alt_text']; ?>" class="csow-slider-image"/>
                            <div class="csow-slide-caption"><?php echo $slide['text']; ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>

                <?php if( count($slides) > 1 ): ?>
                <div class="csow-slider-navs">
                    <a href="#" class="csow-slider-nav prev"><i class="fas fa-arrow-left"></i> <?php _e('Previous', 'cmssquad-sow'); ?></a>
                    <a href="#" class="csow-slider-nav next"><i class="fas fa-arrow-right"></i> <?php _e('Next', 'cmssquad-sow'); ?></a>
                </div>
                <?php endif; ?>

            </div>
    <?php if($type == "fullscreen"): ?>
        </div>
        <div class="csow-slider-filler"></div>
    </div>
    <?php endif; ?>
</div>