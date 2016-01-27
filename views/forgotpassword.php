<div id="page" class="ui top aligned very relaxed stackable grid container">
   <div class="eight wide centered column">
        <div class="ui segments">
            <div class="ui raised segment">
                
                    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
                        <?php foreach ( $attributes['errors'] as $error ) : ?>
                            <div class="ui error message">
                                <i class="close icon"></i>
                                <div class="header">
                                   <?php echo $error; ?>
                                </div>                
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                <div class="ui form">
                    <h2>Recover your password</h2>
                    <p>Enter your username or email address and we'll send you a link.</p>

                    <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
                        <div class="ui field">
                            <input type="text" name="user_login" id="user_login" placeholder="Enter your email address" required>
                        </div>                 
                        <input class="ui button" type="submit" name="submit" class="lostpassword-button" value="Recover Password"/>
                    </form>

                </div>

            </div>
        </div>
    </div>
</div>
