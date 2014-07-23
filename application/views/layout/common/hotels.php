    <div class="guide">
        <div class="leisure">
        <?php if(!empty($s_area_hotels)) : ?>
            <h2><?php echo $hotel_title; ?></h2>
            <?php foreach ($s_area_hotels as $key => $chunk) : ?>
                <?php if($key == $stop_line) break;?>
                <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                <?php foreach ($chunk as $hotel) : ?>
                    <div class="box">
                        <a href="<?php echo '/spring/hotel/'.$spring->id.'/'.$hotel['HotelID'].'/'.$all_springs[$spring->id]->area_id; ?>">
                        
                        <div class="photo spot"><?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $hotel['HotelName']; ?></span></div>
                        <div class="text">
                            <p><?php echo $hotel['HotelCatchCopy']; ?></p>
                            <p><?php echo $hotel['PictureCaption']; ?></p>
                        </div>
                        <img src="/images/icon_leisure_01.png" alt="温泉" class="category" />
                        </a>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php if(!empty($o_area_hotels)) : ?>
                <h2><?php echo $hotel_title; ?></h2>
                <?php foreach ($o_area_hotels as $o_area_hotel_spring_id => $o_area_hotel) : ?>
                    <?php foreach ($o_area_hotel as $key => $chunk) : ?>
                        <?php if($key == $stop_line) break;?>
                        <div class="line<?php if($key >= $this->config->item('sp_display_number')) echo ' undisp'; ?> cf">
                        <?php foreach ($chunk as $hotel) : ?>
                            <div class="box">
                                <a href="<?php echo '/spring/hotel/'.$o_area_hotel_spring_id.'/'.$hotel['HotelID'].'/'.$all_springs[$o_area_hotel_spring_id]->area_id; ?>">
                                <div class="photo spot"><?php echo img(array('src' => $hotel['PictureURL'], 'alt' => $hotel['HotelName'])); ?><div class="shadow">&nbsp;</div><span><?php echo $hotel['HotelName']; ?></span></div>
                                <div class="text">
                                    <p><?php echo $hotel['HotelCatchCopy']; ?></p>
                                    <div class="catch"><?php echo $hotel['HotelType']; ?></div>&nbsp;<div class="catch">In:<?php echo $hotel['CheckInTime']; ?></div>&nbsp;<div class="catch">Out:<?php echo $hotel['CheckOutTime']; ?></div>
                                </div>
                                <img src="/images/icon_leisure_01.png" alt="温泉" class="category" />
                                </a>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
