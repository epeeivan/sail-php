<div class="sail-error-displayer">
    <div class="content">
        <div class="box">
            <h1 class="title">
                <?= $error->getMessage() ?>
            </h1>

        </div>
        <!--  -->
        <table class="sail-table">
            <tr>
                <td>code :</td>
                <td><span style="color:red"><?= $error->getCode() ?></span></td>
            </tr>
            <tr>
                <td>in :</td>
                <td><span style="color:red"><?= $error->getFile() ?></span></td>
            </tr>
            <tr>
                <td>on line :</td>
                <td><span style="color:red"><?= $error->getLine() ?></span></td>
            </tr>
        </table>
        <div>
            <?php
            $trace = $error->getTrace();
            ?>
            <h2 class="title">Trace</h2>
            <table class="sail-table">
                <thead>
                    <tr>
                        <?php
                        if (isset($trace[0])) {
                            foreach ($trace[0] as $key => $value) {
                                # code...
                        ?>
                                <th><?= $key ?></th>
                        <?php
                            }
                        }
                        ?>
                    </tr>
                </thead>

                <?php
                for ($i = 0; $i < count($trace); $i++) {
                    # code...
                ?>
                    <tr>
                        <?php
                        foreach ($trace[$i] as $key => $value) {
                            # code...
                        ?>
                            <td>
                                <?php
                                if (is_array($value)) {
                                    foreach ($value as $key1 => $value1) {
                                        # code...
                                        if (is_string($value1)) {
                                ?>
                                            <div><?= $value1 ?></div>
                                        <?php
                                        } else {
                                        ?>
                                            <div><?= gettype($value1) ?></div>
                                <?php
                                        }
                                    }
                                } else {
                                    echo $value;
                                }
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>

            </table>
        </div>
    </div>
</div>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap');

    .sail-error-displayer {
        font-family: "inter";
        font-size: 12px;
        display: flex;
        width: 100%;
    }
    .sail-error-displayer .content{
        width: 70%;
        margin: auto;
    }

    .title {
        font-weight: bold;
        font-size: 25px;
    }


    .box {
        /* box-shadow: 0px 0px 5px ; */
        padding: 10px 0px;
        border-radius: 5px;
    }

    .sail-table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12;
    }

    .sail-table td,
    .sail-table th {
        padding: 5px 10px;
        font-size: 13px;
    }

    caption {
        font-weight: bold;
        text-align: left;
        color: #333;
    }

    .sail-table thead {
        background-color: #333;
        color: white;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 2%;
        text-align: left;
    }

    .sail-table tbody tr:nth-child(odd) {
        background-color: #fff;
    }

    .sail-table tbody tr:nth-child(even) {
        background-color: #eee;
    }
</style>