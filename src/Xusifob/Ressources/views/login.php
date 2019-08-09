<!--<div class="container">
    <h3>Connectez-vous à votre compte Tinder</h3>

    <form action="" id="login-form" method="POST">

        <div class="form-group">
            <label for="token">Tinder X-Auth-Token</label>
            <input type="text" name="X-Auth-Token" placeholder="X-Auth-Token" value="<?php echo $token; ?>" class="form-control">
            <input type="submit" class="btn btn-primary">
        </div>

    </form>
</div>
 -->

<div class="container mg-top-100">
    <div class="row">
        <div class="col-6 offset-3">
            <form method="POST" action="login/sms" class="jumbotron">
                <h4 class="text-center">Connectez-vous à votre compte Tinder</h4>
                <p class="text-center">Entrez votre numéro de téléphone pour vous connecter à votre compte</p>
                <div class="form-group">
                    <label for="tel">Numéro de téléphone (avec identifiant de pays)</label>
                    <input type="tel" placeholder="+33659559669" name="tel" id="tel" class="form-control">
                </div>
                <input type="submit" class="btn btn-primary btn-lg">
            </form>
        </div>
    </div>
</div>