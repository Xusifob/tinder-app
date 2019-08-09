<div class="container mg-top-100">
    <div class="row">
        <div class="col-6 offset-3">
            <form method="POST" action="login/sms/confirm" class="jumbotron">
                <h4 class="text-center">Terminez votre connexion</h4>
                <p class="text-center">Entrez le code à 6 chiffres reçu par SMS</p>
                <div class="form-group">
                    <label for="code">Code à 6 chiffres</label>
                    <input type="text" placeholder="Code reçu par sms" name="code" id="code" class="form-control">
                    <input type="hidden" name="tel" value="<?php echo $tel ; ?>" >
                </div>
                <input type="submit" class="btn btn-primary btn-lg">
            </form>
        </div>
    </div>
</div>