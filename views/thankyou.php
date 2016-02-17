<div id="page" class="ui top aligned very relaxed stackable grid container">
   <div class="ten wide centered column">
        <div class="ui segments">
            <div class="ui raised segment">
                
                    <h2>You have successfully confirmed your account. <br />Thank you for signing up!</h2>
                    <br />
                    <form class="ui form" action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('https://feedburner.google.com/fb/a/mailverify?uri=pinoyrunners', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
                      <h3 class="ui header">
                        Subscribe to our newsletter to receive new events and updates.
                      </h3>    
                      <div class="content">
                        <div class="inline fields">
                          <div class="required six wide field">
                            <div class="ui input">       
                              <input id="subscriber_name" type="text" placeholder="Enter your name" required>            
                            </div>
                          </div>
                          <div class="required ten wide field">    
                            <div class="ui input">         
                            <input id="subscriber_email" type="text" name="email" placeholder="Enter your email address" value="<?php echo $this->email; ?>" required>
                            </div> 
                          </div>
                          <div class="field">    
                            <input type="hidden" value="pinoyrunners" name="uri"/><input type="hidden" name="loc" value="en_US"/>
                            <button type="submit" id="subscribe2" class="ui teal button" value="Subscribe" >Subscribe</button>
                          </div>
                        </div>
                        <div class="fb-like" data-href="https://pinoyrunners.co" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                      </div>      
                    </form>

            </div>

            <div class="ui secondary segment">
                <a class="ui labeled icon blue huge button" href="<?php echo home_url( 'login' ); ?>">
                    <i class="unlock alternate icon"></i>
                    Next, login and update your profile.
                </a>
            </div>
        </div>
    </div>
</div>

