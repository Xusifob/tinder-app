<style>
    .user {
        background: #fff;
        box-shadow: 0 0 20px 3px rgba(0,0,0,0.5);
        margin-bottom: 30px;
    }

    .alert-like {
        background: #0aab0a;
    }
    .alert-add-to-favorite, .alert-remove-from-favorite {
        background: #fec007;
    }

    .alert-unlike,.alert-limit_exceeded {
        background: red;
    }

    .alert-superlike {
        background: #0d29ff;
    }

    .my-alert {
        display: none;
        z-index: 999;
        position: fixed;
        bottom: 15px;
        right: 15px;
        padding: 15px 25px;
        color: #fff;
        font-weight: bold;
    }

    .user h3 {
        margin-top: 10px;
    }

    .user-content {
        padding: 0 15px;
    }

    .jumbotron {
        position: relative;
    }

    .top-right {
        position: relative;
        top: 10px;
        right: 10px;
        float: right;
        margin-top: -30px;
        margin-right: 10px;
    }

    .btn-group {
        display: block;
        width: 100%;
        text-align: center;
    }

    .btn-group .btn {
        width: 33.333%;
        margin: 0;
        border-radius: 0;
        float: left;
    }

    .user-content p {
        margin-bottom: 0;
    }

    .user-content .bio-label {
        margin-top: 15px;
        display: block;
    }

    .user-content p span {
        color: #868686;
    }

    .user-content .bio {
        color: #868686;
        margin-bottom: 20px;
    }

    .mg-top-20 {
        margin-top: 20px;
    }
    .mg-top-50 {
        margin-top: 50px;
    }

    .owl-nav {
        position: absolute;
        top: 50%;
        left: 5px;
        right: 5px;
    }

    .owl-nav button{
        font-size: 60px !important;
        color: #565656;
    }

    .owl-nav .owl-next {
        position: absolute;
        right: 0;
    }

    .favorite {
        position: absolute;
        z-index: 999;
        top: 5px;
        right: 20px;
        font-size: 30px;
        cursor: pointer;
        color: #fec007;
    }

</style>

<nav class="navbar navbar-expand-lg  navbar-dark bg-primary navbar-fixed sticky-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#gold-section">Golds</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#favorites-section">Favorites</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#matches-section">Matchs</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#geoloc-section">Géolocalisation</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#profile-section">Profil</a>
            </li>
        </ul>
    </div>
    <div class="form-inline my-2 my-lg-0">
        <ul class="navbar-nav mr-0">
            <li class="nav-item active">
                <a  class="nav-link btn btn-danger" href="logout">Déconnexion</a>
            </li>
        </ul>
    </div>
</nav>


<div class="container mg-top-50">

    <div class="jumbotron favorite-profiles hidden" id="favorites-section">
        <div class="clearfix"></div>
        <h3>Vos profils favoris</h3>

        <div class="row" id="favorites">

        </div>
    </div>



    <div class="jumbotron" id="matches-section">
        <a href="javascript:" class="btn btn-primary reload top-right">Recharger les profils</a>
        <a href="javascript:" class="btn btn-warning start-bot top-right">Lancer le bot</a>
        <a href="javascript:" class="btn btn-success like-all top-right">Liker tous les profils</a>
        <a href="javascript:" class="btn btn-danger unlike-all top-right">Passer tous les profils</a>

        <div class="clearfix"></div>
        <h3 class="mg-top-20">Vos Matchs possibles</h3>

        <p>Nombre de matchs trouvés : <strong id="matches-nb"></strong></p>

        <div class="row" id="matches">

        </div>
    </div>

    <div class="jumbotron" id="gold-section">
        <h3>Votre Tinder Gold</h3>

        <p>Nombre de golds trouvés : <strong id="golds-nb"></strong></p>

        <div class="row" id="golds">

        </div>
    </div>


    <div class="jumbotron" id="geoloc-section">
        <h3>Votre localisation</h3>
        <img src="" class="geoloc-img" alt="" style="max-width: 100%;">


        <form action="" id="geoloc-form">
            <label for="geoloc">Modifier votre localisation</label>
            <input type="text" value="" class="form-control" id="geoloc" name="geoloc" >
            <input type="submit">
        </form>

    </div>


    <div class="jumbotron" id="profile-section">
        <h3>Vos informations</h3>

        <!--
        <form id="profile">

            <input type="text">

        </form>
        -->
        <pre class="profile"></pre>
    </div>


</div>


<script type="text/template" class="matches-template favorites-template">
    <div class="col-12 col-md-3 col-lg-4" data-id="">
        <div class="user">
            <i class="far fa-star add-to-favorite favorite"></i>
            <i class="fa fa-star remove-from-favorite favorite"></i>
            <div class="owl-carousel">
            </div>
            <div class="user-content">
                <h2 class="name"></h2>
                <p>Année de naissance : <span class="date"></span></p>
                <p class="school-wrapper">Ecole : <span class="school"></span></p>
                <p class="job-wrapper">Job : <span class="job"></span></p>
                <p>Distance : <span class="distance"></span> <span>Km</span></p>
                <p>Id : <span class="id"></span></p>
                <div class="bio-wrapper">
                    <strong class="bio-label">Bio</strong>
                    <p class="bio"></p>
                </div>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success action" data-action="like">Like</button>
                <button type="button" class="btn btn-primary action" data-action="superlike">Superlike</button>
                <button type="button" class="btn btn-danger action" data-action="unlike">Passer</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</script>

<script type="text/template" class="golds-template">
    <div class="col-12 col-md-3 col-lg-4" data-id="">
        <div class="user">
            <div class="owl-carousel">

            </div>
            <p class="id"></p>
        </div>
    </div>
</script>

<div class="my-alert alert-like">
    Like pris en compte
</div>
<div class="my-alert alert-add-to-favorite">
    Profile ajouté aux favoris
</div>
<div class="my-alert alert-remove-from-favorite">
    Profile retiré des favoris
</div>
<div class="my-alert alert-superlike">
    Super like pris en compte
</div>
<div class="my-alert alert-limit_exceeded">
    Vous avez dépassé votre limite de superlike !
</div>
<div class="my-alert alert-unlike">
    Dislike pris en compte
</div>
