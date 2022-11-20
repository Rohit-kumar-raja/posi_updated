

    <div class="tabs-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-xs-12 tabs-body">
                    <div class="tabs">

                        <div class="tab-2 tab-1">
                            <a class="first-tab" href="../dashboard.php"> Feed </a>
                            <input id="tab2-2" name="tabs-two" type="radio" <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'checked': '' ?>>
                        </div>

                        <div class="tab-2">
                            <a class="first-tab" href="../battle/battle.php"> Battle </a>
                            <input id="tab2-1" name="tabs-two" type="radio" <?= basename($_SERVER['PHP_SELF']) == 'battle.php' ? 'checked': '' ?>>
                        </div>

                        <div class="tab-2">
                            <a href="../battle/board.php" class="first-tab"> Board </a>
                            <input id="tab2-3" name="tabs-two" type="radio" <?= basename($_SERVER['PHP_SELF']) == 'board.php' ? 'checked': '' ?>>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
 