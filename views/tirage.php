
<div class="card container col-md-10 mt-5 mb-5"  style="margin-top: 90px!important;">
  <div class="card-body">
    <div class="row mb-3">

        <h5 class="card-title col-md-8">La liste des challenges</h5>
        <div class="col-md-4 text-end">
            <a href="?page=challenge&type=add" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Ajouter</a>
        </div>
    </div>
    <?php require_once("views/includes/getmessage.php"); ?>

    <div class="container bracket">
        <div class="round">
            <div class="match">Leinster (1) vs Ulster (9)</div>
            <div class="brace"></div>
            <div class="match">Leicester (4) vs Edimbourg (5) <span class="arrow">➡</span></div>
            <div class="match">Toulouse (2) vs Bulls (7) <span class="arrow">➡</span></div>
            <div class="brace"></div>
            <div class="match">Sharks (3) vs Munster (6)</div>
        </div>
        <div class="round">
            <div class="match">Quart de finale</div>
            <div class="match">Quart de finale</div>
        </div>
        <div class="round">
            <div class="match"><span class="arrow-right">➡</span>🏆 Demi-finale 🏆<span class="arrow">⬅</span></div>
        </div>
        <div class="round">
            <div class="match">Quart de finale</div>
            <div class="match">Quart de finale</div>
        </div>
        <div class="round">
            <div class="match">La Rochelle (1) vs Gloucester (8)</div>
            <div class="brace-right"></div>
            <div class="match"><span class="arrow-right">⬅</span>Saracens (4) vs Ospreys (5)</div>
            <div class="match"><span class="arrow-right">⬅</span>Exeter (2) vs Montpellier (7)</div>
            <div class="brace-right"></div>
            <div class="match">Stormers (3) vs Harlequins (6)</div>
        </div>
    </div>
  </div>
</div>

<header>
    <style>
        .bracket {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .round {
            margin: 20px;
            position: relative;
        }
        .match {
            border: 2px solid #0d6efd;
            padding: 10px;
            border-radius: 10px;
            background-color: #f8f9fa;
            margin-bottom: 15px;
            position: relative;
        }
        .arrow {
            position: absolute;
            top: 50%;
            right: -30px;
            transform: translateY(-50%);
            font-size: 24px;
            color: #0d6efd;
        }
        .arrow-right {
            position: absolute;
            top: 50%;
            left: -30px;
            transform: translateY(-50%);
            font-size: 24px;
            color: #0d6efd;
        }
        .trophy {
            font-size: 3rem;
            color: gold;
            margin-top: 50px;
        }
        .brace {
            float: left;
            width: 10px;
            height: 70px;
            border-left: 2px solid #0d6efd;
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
            margin-left: -15px;
            margin-top: -40px;
        }
        .brace-right {
            float: right;
            width: 10px;
            height: 70px;
            border-right: 2px solid #0d6efd;
            border-top: 2px solid #0d6efd;
            border-bottom: 2px solid #0d6efd;
            margin-right: -15px;
            margin-top: -40px;
        }
    </style>
</header>