<style>
    .user {
        background: #fff;
        box-shadow: 0 0 20px 3px rgba(0,0,0,0.5);
        margin-bottom: 30px;
    }

    .alert-like {
        background: #0aab0a;
    }

    .alert-unlike {
        background: red;
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
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .btn-group {
        display: block;
        width: 100%;
        text-align: center;
    }

    .btn-group .btn {
        width: 50%;
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

</style>

<a href="logout" class="btn btn-danger">Logout</a>

<div class="container">
    <div class="jumbotron">
        <h3>Votre Tinder Gold</h3>

        <p>Nombre de golds trouvés : <strong id="golds-nb"></strong></p>

        <div class="row" id="golds">

        </div>
    </div>

    <div class="jumbotron">
        <h3>Vos Matchs possibles</h3>

        <a href="javascript:" class="btn btn-primary reload top-right">Recharger les profils</a>

        <p>Nombre de matchs trouvés : <strong id="matches-nb"></strong></p>

        <div class="row" id="matches">

        </div>
    </div>

    <div class="jumbotron">
        <h3>Votre localisation</h3>
        <img src="" class="geoloc-img" alt="" style="max-width: 100%;">


        <form action="" id="geoloc-form">
            <label for="geoloc">Modifier votre localisation</label>
            <input type="text" value="" class="form-control" id="geoloc" name="geoloc" >
            <input type="submit">
        </form>

    </div>


    <div class="jumbotron">
        <h3>Vos informations</h3>
        <pre class="profile"></pre>
    </div>


</div>


<script type="text/template" id="matches-template">
    <div class="col-12 col-md-3 col-lg-4" data-id="">
        <div class="user">
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
                <!-- <button type="button" class="btn btn-primary action" data-action="superlike">Superlike</button> -->
                <button type="button" class="btn btn-danger action" data-action="unlike">Unlike</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</script>

<script type="text/template" id="golds-template">
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
<div class="my-alert alert-unlike">
    Dislike pris en compte
</div>
