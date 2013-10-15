{include file="file:[$THEME]header.tpl" TITLE="Home"}

<!--INCLUDE HEADER.php -->
            
            	<!-- Statistics Button Container 
            	<div class="TAGS-RailRoad-stat-container clearfix">
                	
                    <!-- Statistic Item 
                	<a class="TAGS-RailRoad-stat" href="#">
                    	<!-- Statistic Icon (edit to change icon) 
                    	<span class="TAGS-RailRoad-stat-icon icol32-building"></span>
                        
                        <!-- Statistic Content 
                        <span class="TAGS-RailRoad-stat-content">
                        	<span class="TAGS-RailRoad-stat-title">Floors Climbed</span>
                            <span class="TAGS-RailRoad-stat-value">324</span>
                        </span>
                    </a>

                </div>-->
                
                <!-- Panels Start -->
                
            	
                {foreach from=$posts item=key}
            	<div class="TAGS-RailRoad-panel grid_6">
                	<div class="TAGS-RailRoad-panel-header">
                    	<span><i class="icon-book"></i> <a href="index.php?mod=Blog&act=view&pid={$key->post_id}">{$key->subject}</a></span>
                    </div>
                    <div class="TAGS-RailRoad-panel-body no-padding">
                        <ul class="TAGS-RailRoad-summary clearfix">
                            <li>
                                <span class="val">
                                    <span class="text-nowrap">{$key->body}</span>
                                </span>
                            </li>
                        </ul>
						<span><i class="icon-user"></i> {$key->author_name}</span><br/>
						<span><i class="icon-book"></i> Posted: {$key->posted|date_format:'%A %m/%d/%Y'}</span><br/>
						<span><i class="icon-book"></i> Category: {$key->cat_title}</span>
                    </div>
					
                </div>
                {/foreach}

                
                
                <!-- Panels End -->
            </div>
            <!-- Inner Container End -->
                       
            <!-- Footer -->
            <div id="TAGS-RailRoad-footer">
            	Copyright Your Website 2012. All Rights Reserved.
            </div>
            
        </div>
        <!-- Main Container End -->
        
    </div>

    <!--INCLUDE FOOTER.php -->

{include file="file:[$THEME]footer.tpl"}