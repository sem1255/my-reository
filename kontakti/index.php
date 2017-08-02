<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("��������");
?>
<script type="text/javascript">
    this.SetAddresses = function (results)
    {
        $(".address_list").show();
        $(".address_list").empty();
        var addressText = _this.ComposeAddress(results[0]); ...
    }

    //Составить строку адреса по первому результату
    this.ComposeAddress = function (item) {
        retAddress = "";
        $.each(item.address_components, function (i, address_item) {
            var isOk = false;
            $.each(address_item.types, function (j, typeName) {
                //не будем брать значения адреса улицы и локали (города) - город потом будет в administrative_level_2
                if (typeName != "street_address" && typeName != "locality") {
                    isOk = true;
                }
            });
            if (isOk) {
                if (retAddress == "") {
                    retAddress = address_item.long_name;
                } else {
                    retAddress = retAddress + ", " + address_item.long_name;
                }
            }
        });
        return retAddress;
    }
</script>
    <section id="banner">
        <header>
            <h2>Adress: <em>city Chernivtsi, street Golovna 178, dom 12, kv 5 <a href="http://html5up.net">HTML5 UP</a></em></h2>
            <a href="#footer" class="button">Nupusatu povidomlenya</a>
        </header>
    </section>
    <!-- Posts -->
    <section class="wrapper style1">
        <div class="container">
            <div class="row">
                <section class="6u 12u(narrower)">
                    <div class="box post">
                        <h3>Kontact</h3>
                        <p>city Chernivtsi, street Golovna 178, dom 12, kv 5<br>
                            +380687274376<br>
                            <a href="mailto:manager@magazin,ru">manager@magazin,ru</a>
                        </p>
                        <p>Time working pn - pt з 9:30 do 21:00

                        </p>
                    </div>
                </section>
                <section class="6u 12u(narrower)">
                    <div class="box post">
                        <a href="#" class="image left"><img src="images/pic02.jpg" alt=""/></a>
                        <div class="inner">
                            <h3>OOO &laquo;Magazin&laquo;</h3>
                            <p>INN 678456938<br>
                                KPP 5498789<br>
                                BUK 54897894<br>
                                OKPO 438684829<br>
                                OKATO 5467868<br>
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>