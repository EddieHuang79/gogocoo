<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>歡迎來到 GoGoCOO! 很開心能在此相遇!</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ URL::asset('css/agency.css') }}">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ URL::asset('css/font-awesome.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Start GoGoCOO</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">電商服務</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">電商教室</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#about">關於我們</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">聯繫我們</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="/login">登入/註冊</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in">歡迎來到 GoGoCOO!</div>
                <div class="intro-heading">很開心能在此相遇!</div>
                <a class="page-scroll btn btn-xl" href="/login">免費試用</a>
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">電商服務</h2>
                    <h3 class="section-subheading text-muted">雲智慧電商營運工具.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-shopping-cart fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">訂單整合管理系統</h4>
                    <p class="text-muted">整合來自各個平台的訂單，完整掌握訂單狀態.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">智慧出貨管理系統</h4>
                    <p class="text-muted">快速產出發貨所需資料，提高物流發貨效率.</p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">智慧營運分析系統</h4>
                    <p class="text-muted">透過AI機器人提供數據營運分析，見微知著洞燭機先.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid Section -->
    <section id="portfolio" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">電商教室</h2>
                    <h3 class="section-subheading text-muted">跟您分享提高營運效率的幾個好方法.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="webimage/portfolio/roundicons.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>訂單好難管</h4>
                        <p class="text-muted">訂單整理蹲馬步三步驟</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="webimage/portfolio/startup-framework.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>三倍發貨快手</h4>
                        <p class="text-muted">出貨提升三倍快又能減少出錯!?</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="webimage/portfolio/treehouse.png" class="img-responsive" alt="">
                    </a>
                    <div class="portfolio-caption">
                        <h4>數據會說話</h4>
                        <p class="text-muted">茫茫訂單海的小秘密</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">關於我們</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="webimage/about/1.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2014-2015</h4>
                                    <h4 class="subheading">我們的起點</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">東西賣好好幹嘛搞電商，這時代普遍的概念，但對於被通路壟斷的我們，還是試著架站自己賣看看!</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="webimage/about/2.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>2015-2016</h4>
                                    <h4 class="subheading">血淚的時代</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">在人人電子商務的時代，終於把店鋪開了出來，好不容易也把訂單做了出來，但是四散的訂單與充斥人工的作業流程，都是這段時間血淚交織的痛!!</p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="timeline-image">
                                <img class="img-circle img-responsive" src="webimage/about/3.jpg" alt="">
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4>Early 2017</h4>
                                    <h4 class="subheading">華麗的轉身</h4>
                                </div>
                                <div class="timeline-body">
                                    <p class="text-muted">透過解決自己的需求找到真正的痛點，透過技術的不斷突破，各式各樣簡單便利的智慧功能，如您所見!!</p>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-inverted">
                            <div class="timeline-image">
                                <h4>一起開創
                                    <br>屬於我們的
                                    <br>故事!</h4>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Aside -->
    <aside class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="webimage/logos/envato.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="webimage/logos/designmodo.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="webimage/logos/themeforest.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="webimage/logos/creative-market.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">聯絡我們</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" action="/sendmail" method="POST" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" name="phone" class="form-control" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea name="message" class="form-control" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">送出訊息</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; GoGoCOO 2017</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#portfolioModal1" class="portfolio-link" data-toggle="modal">隱私權政策</a></li>
                        <li><a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">服務條款</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Portfolio Modal 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>隱私權政策</h2>
                            <p class="item-intro text-muted">GoGoCOO必須確保隱私萬無一失</p>
                            <img class="img-responsive img-centered" src="webimage/portfolio/roundicons-free.png" alt="">
                            <p>GoGoCOO非常重視用戶的隱私權，因此制訂了隱私權保護政策，請您詳閱以下內容。
                              · 隱私權保護政策內容包括本網站如何處理由用戶使用網站服務時所收集到的身份識別資料，也包括本網站如何處理在商業伙伴與本網站合作時分享的任何身份識別資料。
                              · 隱私權保護政策並不適用於賣客以外的公司、非賣客所僱用或管理的人員。
                              · 當您註冊帳號、使用本網站的產品或服務、瀏覽本網站網頁、參加活動或贈獎時，GoGoCOO會收集您的個人識別資料，為保障您的隱私及安全，您的帳號資料會用密碼保護。
                              · 本網站也可以從商業夥伴處取得個人資料。
                              · GoGoCOO會自動接收並紀錄您瀏覽器上的伺服器數值(包括IP Address 、GoGoCOO cookie中的資料及您要求取用的網頁紀錄)。
                              · 您有隨時修改帳號資料的權力，其中並包括接受賣客通知您特別活動或新產品的決定權。
                              · 您的資料本網站將會作以下用途使用：
                               1.改進為您提供的廣告及網頁內容。
                               2.完成您對某項產品的要求及通知特別活動或新產品。
                              ·  GoGoCOO不會向任何人出售或出借您的個人識別資料。
                              ·  在以下的情況下，GoGoCOO會向政府機關、合作伙伴或公司提供您的個人識別資料：
                               1.需要與合作伙伴或公司共用您的資料，才能夠提供您要求的產品或服務時。
                               2.向代表GoGoCOO提供服務或產品的公司提供資料，以便向您提供產品或服務 (若未經事先通知，這些公司均無權使用本網站所提供的個人資料，作提供產品或服務以外的其他用途)。
                               3.依照法令規定或政府機關的正式函文要求。
                               4.GoGoCOO發覺您在網站上的行為違反賣客服務條款或產品、服務的特定使用指南。
                              · 為了保護使用者個人隱私， 我們無法為您查詢其他使用者的帳號資料，請您見諒！若您有相關法律上問題需查閱他人資料時，請務必向警政單位提出告訴，我們將全力配合警政單位調查並提供所有相關資料以協助調查。
                              · GoGoCOO會到您的電腦設定並取用GoGoCOOcookie。
                              · GoGoCOO容許在我們網頁上擺放廣告的廠商到您的電腦設定並取用cookie，其他公司將根據其自訂的隱私權保護政策，而並非本政策使用其cookie，其他廣告商或公司不能提取GoGoCOO的cookie。
                              · 當GoGoCOO進行與其產品及服務有關的工作時，會進入我們的網站網絡，提取cookie使用。
                              · 在部分情況下賣客使用通行標準的SSL加密機制，以保障資料傳送的安全。
                            </p>
                            <p>
                                <strong>GoGoCOO得隨時修訂本政策，當我們在使用個人資料的規定上作出大幅度修改時，我們會在網頁上張貼告示，通知您相關事項。</strong>
                            </p>
                            <ul class="list-inline">
                                <li>Date: March 2018</li>
                                <li>Category: Policy</li>
                            </ul>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> 關閉視窗</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 2 -->
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>條款與細則</h2>
                            <p class="item-intro text-muted">來列一下彼此的注意事項</p>
                            <img class="img-responsive img-centered" src="webimage/portfolio/startup-framework-preview.png" alt="">
                            <p>·  一、認知與接受條款
                                1.GoGoCOO(以下簡稱「COO」)係依據本服務條款提供服務 (以下簡稱「本服務」)。當會員完成COO之會員註冊手續、或開始使用本服務時，即表示已閱讀、瞭解並同意接受本服務條款之所有內容，並完全接受本服務現有與未來衍生的服務項目及內容。COO有權於任何時間修改或變更本服務條款之內容，修改後的服務條款內容將公佈網站上，COO將不會個別通知會員，建議會員隨時注意該等修改或變更。會員於任何修改或變更後繼續使用本服務時，視為會員已閱讀、瞭解並同意接受該等修改或變更。若不同意上述的服務條款修訂或更新方式，或不接受本服務條款的其他任一約定，會員應立即停止使用本服務。 2.若會員為未滿二十歲之未成年人，應於會員的家長（或監護人）閱讀、瞭解並同意本約定書之所有內容及其後修改變更後，方得註冊為會員、使用或繼續使用本服務。當會員使用或繼續使用COO時，即推定會員的家長（或監護人）已閱讀、瞭解並同意接受本約定書之所有內容及其後修改變更。 3.會員及COO雙方同意使用本服務之所有內容包括意思表示等，以電子文件作為表示方式。
                                ·  二、會員的註冊義務
                                為了能使用本服務，會員同意以下事項：
                                ·        依本服務註冊表之提示提供會員本人正確、最新的資料，且不得以第三人之名義註冊為會員。每位會員僅能註冊登錄一個帳號，不可重覆註冊登錄。
                                ·        即時維持並更新會員個人資料，確保其正確性，以獲取最佳之服務。
                                ·        若會員提供任何錯誤或不實的資料、或未按指示提供資料、或欠缺必要之資料、或有重覆註冊帳號等情事時，COO有權不經事先通知，逕行暫停或終止會員的帳號，並拒絕會員使用本服務之全部或一部。
                                ·  三、COO隱私權政策
                                關於會員的註冊以及其他特定資料依COO「隱私權政策」受到保護與規範。
                                ·  四、會員帳號、密碼及安全
                                1.完成本服務的登記程序之後，會員將取得一個特定之密碼及會員帳號，維持密碼及帳號之機密安全，是會員的責任。任何依照規定方法輸入會員帳號及密碼與登入資料一致時，無論是否由本人親自輸入，均將推定為會員本人所使用，利用該密碼及帳號所進行的一切行動，會員本人應負完全責任。
                                2.會員同意以下事項：
                                ·        會員的密碼或帳號遭到盜用或有其他任何安全問題發生時，會員將立即通知COO
                                ·        每次連線完畢，均結束會員的帳號使用。
                                ·        會員的帳號、密碼及會員權益均僅供會員個人使用及享有，不得轉借、轉讓他人或與他人合用。
                                ·        帳號及密碼遭盜用、不當使用或其他COO無法辯識是否為本人親自使用之情況時，對此所致之損害，除證明係因可歸責於COO之事由所致，COO將不負任何責任。
                                ·        COO若知悉會員之帳號密碼確係遭他人冒用時，將立即暫停該帳號之使用(含該帳號所生交易之處理)。
                                ·  五、兒童及青少年之保護
                                為確保兒童及青少年使用網路的安全，並避免隱私權受到侵犯，家長（或監護人）應盡到下列義務：未滿十二歲之兒童使用本服務時時，應全程在旁陪伴，十二歲以上未滿十八歲之青少年使用本服務前亦應斟酌是否給予同意。
                                ·  六、使用者的守法義務及承諾
                                會員承諾絕不為任何非法目的或以任何非法方式使用本服務，並承諾遵守中華民國相關法規及一切使用網際網路之國際慣例。會員若係中華民國以外之使用者，並同意遵守所屬國家或地域之法令。會員同意並保證不得利用本服務從事侵害他人權益或違法之行為，包括但不限於：
                                ·        公布或傳送任何誹謗、侮辱、具威脅性、攻擊性、不雅、猥褻、不實、違反公共秩序或善良風俗或其他不法之文字、圖片或任何形式的檔案
                                ·        侵害或毀損COO或他人名譽、隱私權、營業秘密、商標權、著作權、專利權、其他智慧財產權及其他權利
                                ·        違反依法律或契約所應負之保密義務
                                ·        冒用他人名義使用本服務
                                ·        傳輸或散佈電腦病毒
                                ·        從事未經COO事前授權的商業行為
                                ·        刊載、傳輸、發送垃圾郵件、連鎖信、違法或未經COO許可之多層次傳銷訊息及廣告等；或儲存任何侵害他人智慧財產權或違反法令之資料
                                ·        對本服務其他用戶或第三人產生困擾、不悅或違反一般網路禮節致生反感之行為
                                ·        其他不符本服務所提供的使用目的之行為或COO有正當理由認為不適當之行為
                                ·  七、服務內容之變更與電子報及EDM發送
                                1.會員同意COO所提供本服務之範圍，COO均得視業務需要及實際情形，增減、變更或終止相關服務的項目或內容，且無需個別通知會員。 2.會員同意COO得依實際執行情形，增加、修改或終止相關活動，並選擇最適方式告知會員。 3.會員同意COO得不定期發送電子報或商品訊息(EDM)至會員所登錄的電子信箱帳號。當會員收到訊息後表示拒絕接受行銷時，COO將停止繼續發送行銷訊息。
                                ·  八、服務之停止、中斷
                                COO將依一般合理之技術及方式，維持系統及服務之正常運作。但於以下各項情況時，COO有權可以停止、中斷提供本服務：
                                ·        COO網站電子通信設備進行必要之保養及施工時
                                ·        發生突發性之電子通信設備故障時
                                ·        COO網站申請之電子通信服務被停止，無法提供服務時
                                ·        由於天災等不可抗力之因素或其他不可歸責於COO致使COO網站無法提供服務時
                                ·  九、交易行為
                                1.會員使用本服務進行交易時，應依據COO所提供之確認商品數量及價格機制進行。 2.會員同意使用本服務訂購產品時，於COO通知確認交易成立前，COO仍保有不接受訂單或取消出貨之權利。會員向COO發出訂購通知後，系統將自動發出接受通知，但此通知並非訂單確認通知，關於交易成立與否COO將另行告知。若因訂單內容之標的商品或服務，其交易條件(包括但不限於規格、內容說明、圖片、)有誤時，COO仍得於下單後二工作日內拒絕該筆訂單。 3.會員若於使用本服務訂購產品後倘任意退換貨、取消訂單、或有任何COO認為不適當而造成COO作業上之困擾或損害之行為，COO將可視情況採取拒絕交易，或永久取消會員資格辦理。若會員訂購之產品若屬於以下情形：（１）預購類商品（２）商品頁顯示無庫存（３）須向供應商調貨（４）轉由廠商出貨，因商品交易特性之故，倘商品缺絕、或廠商因故無法順利供貨導致訂單無法成立時，COO將以最適方式(以電子郵件為主，再輔以電話、郵遞或傳真等)告知。 4.會員使用本服務進行交易時，得依照消費者保護法之規定行使權利。因會員之交易行為而對本服務條款產生疑義時，應為有利於消費者之解釋。
                                ·  十、責任之限制與排除
                                1.本服務所提供之各項功能，均依該功能當時之現況提供使用，COO對於其效能、速度、完整性、可靠性、安全性、正確性等，皆不負擔任何明示或默示之擔保責任。 2. COO並不保證本服務之網頁、伺服器、網域等所傳送的電子郵件或其內容不會含有電腦病毒等有害物；亦不保證郵件、檔案或資料之傳輸儲存均正確無誤不會斷線和出錯等，因各該郵件、檔案或資料傳送或儲存失敗、遺失或錯誤等所致之損害，COO不負賠償責任。
                                ·  十一、智慧財產權的保護
                                1.COO所使用之軟體或程式、網站上所有內容，包括但不限於著作、圖片、檔案、資訊、資料、網站架構、網站畫面的安排、網頁設計，均由COO或其他權利人依法擁有其智慧財產權，包括但不限於商標權、專利權、著作權、營業秘密與專有技術等。任何人不得逕自使用、修改、重製、公開播送、改作、散布、發行、公開發表、進行還原工程、解編或反向組譯。若會員欲引用或轉載前述軟體、程式或網站內容，必須依法取得COO或其他權利人的事前書面同意。尊重智慧財產權是會員應盡的義務，如有違反，會員應對COO負損害賠償責任（包括但不限於訴訟費用及律師費用等）。 2.在尊重他人智慧財產權之原則下，會員同意在使用COO之服務時，不作侵害他人智慧財產權之行為。 3.若會員有涉及侵權之情事，COO可暫停全部或部份之服務，或逕自以取消會員帳號之方式處理。
                                ·  十二、會員對COO之授權
                                對於會員上載、傳送、輸入或提供之資料，會員同意COO網站得於合理之範圍內蒐集、處理、保存、傳遞及使用該等資料，以提供使用者其他資訊或服務、或作成會員統計資料、或進行關於網路行為之調查或研究，或為任何之合法使用。若會員無合法權利得授權他人使用、修改、重製、公開播送、改作、散布、發行、公開發表某資料，並將前述權利轉授權第三人，請勿擅自將該資料上載、傳送、輸入或提供至COO。任何資料一經會員上載、傳送、輸入或提供至COO時，視為會員已允許COO無條件使用、修改、重製、公開播送、改作、散布、發行、公開發表該等資料，並得將前述權利轉授權他人，會員對此絕無異議。會員並應保證COO使用、修改、重製、公開播送、改作、散布、發行、公開發表、轉授權該等資料，不致侵害任何第三人之智慧財產權，否則應對COO負損害賠償責任（包括但不限於訴訟費用及律師費用等）。
                                ·  十三、特別授權事項
                                因使用本服務所提供之網路交易或活動，可能須透過宅配或貨運業者始能完成貨品(或贈品等)之配送或取回，因此，會員同意並授權COO得視該次網路交易或活動之需求及目的，將由會員所提供且為配送所必要之個人資料(如收件人姓名、配送地址、連絡電話等)，提供予宅配貨運業者及相關配合之廠商，以利完成該次貨品(或贈品等)之配送、取回。
                                ·  十四、拒絕或終止會員的使用
                                會員同意COO得基於維護交易安全之考量，因任何理由，包含但不限於缺乏使用，或違反本服務條款的明文規定及精神，終止會員的密碼、帳號（或其任何部分）或本服務（或其任何部分）之使用，或將本服務內任何「會員內容」加以移除並刪除。此外，會員同意若本服務（或其任何部分）之使用被終止，COO對會員或任何第三人均不承擔責任。
                                ·  十五、準據法與管轄法院

                                本服務條款之解釋與適用，以及與本服務條款有關或會員與COO間因交易行為而產生之爭議或糾紛，應依照中華民國法律予以處理，並以台灣台北地方法院為第一審管轄法院，但若法律對於管轄法院另有強制規定者，仍應依其規定。
                            </p>
                            <p>GoGoCOO得隨時修訂本條款，當我們在服務條款的規定上作出大幅度修改時，我們會在網頁上張貼告示，通知您相關事項。</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> 關閉視窗</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ URL::asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/classie.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/cbpAnimatedHeader.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jqBootstrapValidation.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/contact_me.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/agency.js') }}"></script>

</body>

</html>
