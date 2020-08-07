
<?php

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $galleryHTML = '
            <section id="default-wrapper">
                <div class="container">
                  <div class="custom-gallery">
                    <div id="lightgallery" class="list-unstyled justified-gallery" style="height: 520px;">
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/1.jpg" data-sub-html="sdsdsd">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/1.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/2.jpg" data-sub-html="sdsdsd">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/2.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/3.jpg">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/3.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/4.jpg">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/4.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/5.jpg">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/5.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/6.jpg">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/6.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="images/gallery/7.jpg">
                        <img class="img-responsive" src="'.\URL::to('/').'/theme/artemissalt/images/gallery/7.jpg">
                        <div class="custom-gallery-poster">
                          <img src="'.\URL::to('/').'/theme/artemissalt/images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <!-- <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/8.jpg"
                        style="width: 187px; height: 126.144px; top: 133.144px; left: 382px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-8.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/9.jpg"
                        data-sub-html="sdsdsd" style="width: 187px; height: 126.144px; top: 133.144px; left: 570px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-9.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/10.jpg"
                        style="width: 186px; height: 126.144px; top: 133.144px; left: 758px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-10.jpg"
                          style="width: 186px; height: 127px; margin-left: -93px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/11.jpg"
                        data-sub-html="sdsdsd" style="width: 187px; height: 126.144px; top: 260.287px; left: 6px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-11.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/12.jpg"
                        style="width: 187px; height: 126.144px; top: 260.287px; left: 194px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-12.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/13.jpg"
                        style="width: 187px; height: 126.144px; top: 260.287px; left: 382px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-13.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/14.jpg"
                        style="width: 187px; height: 126.144px; top: 260.287px; left: 570px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-14.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/15.jpg"
                        data-sub-html="sdsdsd" style="width: 186px; height: 126.144px; top: 260.287px; left: 758px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-15.jpg"
                          style="width: 186px; height: 127px; margin-left: -93px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/16.jpg"
                        style="width: 187px; height: 126.144px; top: 387.431px; left: 6px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-16.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/17.jpg"
                        style="width: 187px; height: 126.144px; top: 387.431px; left: 194px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-17.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/18.jpg"
                        style="width: 187px; height: 126.144px; top: 387.431px; left: 382px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-18.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/19.jpg"
                        data-sub-html="sdsdsd" style="width: 187px; height: 126.144px; top: 387.431px; left: 570px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-19.jpg"
                          style="width: 187px; height: 127px; margin-left: -93.5px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a>
                      <a data-pinterest-text="Pin it" data-tweet-text="share it on twitter" class="jg-entry" href="../static/img/20.jpg"
                        style="width: 186px; height: 126.144px; top: 387.431px; left: 758px; opacity: 1;">
                  
                        <img class="img-responsive" src="../static/img/thumb-20.jpg"
                          style="width: 186px; height: 127px; margin-left: -93px; margin-top: -63.5px;">
                        <div class="custom-gallery-poster">
                          <img src="images/gallery/zoom.png">
                        </div>
                  
                      </a> -->
                    </div>
                  </div>
                </div>
              </section>
        ';

        $homeHTML = '
                      <div class="row">
                        <div class="col-lg-4">
                            <div class="service-card primary">
                                <div class="service-icon">
                                    <img src="'.\URL::to('/').'/theme/stpaul/images/misc/img-01.png">
                                </div>
                                <div class="media-body">
                                    <h3 class="service-title">Worldwide Shipping</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-card secondary">
                                <div class="service-icon">
                                    <img src="'.\URL::to('/').'/theme/stpaul/images/misc/img-02.png">
                                </div>
                                <div class="media-body">
                                    <h3 class="service-title">Secure Transactions</a>
                                    </h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="service-card primary">
                                <div class="service-icon">
                                    <img src="'.\URL::to('/').'/theme/stpaul/images/misc/img-03.png">
                                </div>
                                <div class="media-body">
                                    <h3 class="service-title">Fast and Complete</a>
                                    </h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                  ';

        $aboutHTML = '
                        <section id="default-wrapper">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <h3 class="subpage-heading">Quick Links</h3>
                                        <div class="side-menu">
                                            <ul>
                                                <li><a href="#">Lorem ipsum dolor</a></li>
                                                <li><a href="#">Nulla sequi, sint</a></li>
                                                <li><a href="#">Corporis, quos, sit</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="article-content">
                                            <h3 class="subpage-heading">Company Profile</h3>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                            <img src="images/misc/about-img1.jpg" alt="" />
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                                in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                      ';



        $contact_us = '
                        <h3 class="subpage-heading">Contact Details</h3>
                          <iframe class="mt-2 mb-4" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14917.830083111654!2d-73.65783255789836!3d45.465301998048886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc917153ba67f8f%3A0xa508f1e92565d250!2s5544+Avenue+Rosedale%2C+C%C3%B4te+Saint-Luc%2C+QC+H4V+2J1%2C+Canada!5e0!3m2!1sen!2sph!4v1564111296278!5m2!1sen!2sph"
                              width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
                          <div class="row">
                              <div class="col-md-6">
                                  <p>
                                      <strong>Office Location</strong><br />5544 Avenue Rosedale, Cöte Saint-Luc<br />QC H4V 2J1
                                  </p>
                              </div>
                              <div class="col-md-6">
                                  <p>
                                      <strong>Office Location</strong><br />5544 Avenue Rosedale, Cöte Saint-Luc<br />QC H4V 2J1
                                  </p>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-6">
                                  <p>
                                      <strong>Telephone</strong><br />+63 (2) 706-6144<br />+63 (2) 706-5796<br />+63 (2) 511-0528
                                  </p>
                              </div>
                              <div class="col-md-6">
                                  <p>
                                      <strong>Follow Us</strong><br />For more updates, follow us on our social media accounts.
                                  </p>
                              </div>
                          </div>
                        ';

        $footerHTML = '<div class="pre-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-info">
                                <h6 class="footer-title">Contact Info</h6>
                                <p>Location: 7708 St. Paul Road,<br> San Antonio Village 1203 Makati City<br> Trunk Line: 895-9701-04<br> Fax: 519-5585</p>
                                <div class="row">
                                    <div class="col-sm-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-info">
                                <h6 class="footer-title">Connect with Us</h6>
                                <ul class="footer-social-links">
                                    <li>
                                        <a href="#"><span class="fa fa-facebook-f"></span> Facebook</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="fa fa-twitter"></span> Twitter</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="fa fa-youtube-play"></span> Youtube</a>
                                    </li>
                                </ul>
                                <div class="gap-20"></div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="footer-info">
                                <h6 class="footer-title">Customer Care</h6>
                                <ul class="quick-link">
                                    <li><a href="#">International Product Policy</a></li>
                                    <li><a href="">Terms and Shipping Policy</a></li>
                                    <li><a href="">Questions and Suggestions</a></li>
                                    <li><a href="">How to Buy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="footer-info">
                                <h6 class="footer-title">ST PAULS Around The World</h6>
                                <p>Check out other St. Paul’s portal</p>
                                <div class="btn-group">
                                    <button type="button" class="btn dropdown-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      Philippine-Macau
                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Argentina</a>
                                        <a class="dropdown-item" href="#">Australia</a>
                                        <a class="dropdown-item" href="#">Brazil</a>
                                        <a class="dropdown-item" href="#">Canada</a>
                                        <a class="dropdown-item" href="#">Chile</a>
                                        <a class="dropdown-item" href="#">Colombia</a>
                                        <a class="dropdown-item" href="#">France</a>
                                        <a class="dropdown-item" href="#">Germany</a>
                                        <a class="dropdown-item" href="#">India</a>
                                        <a class="dropdown-item" href="#">Ireland</a>
                                        <a class="dropdown-item" href="#">Italy</a>
                                        <a class="dropdown-item" href="#">Japan</a>
                                        <a class="dropdown-item" href="#">Korea</a>
                                        <a class="dropdown-item" href="#">Mexico</a>
                                        <a class="dropdown-item" href="#">Poland</a>
                                        <a class="dropdown-item" href="#">Portugal</a>
                                        <a class="dropdown-item" href="#">Rep. Of Congo</a>
                                        <a class="dropdown-item" href="#">Spain</a>
                                        <a class="dropdown-item" href="#">U.K.</a>
                                        <a class="dropdown-item" href="#">U.S.A.</a>
                                        <a class="dropdown-item" href="#">Venezuela</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="post-footer">
                <div class="container">
                    <p>
                        © Catholic Online Bookstore in Philippines St. Pauls Online | <span class="white-spc">Philippine - Macau Province 2019</span>
                    </p>
                </div>
            </div>';

      
        $pages = [
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'home',
                'name' => 'Home',
                'label' => 'Home',
                'contents' => $homeHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Home',
                'meta_keyword' => 'home',
                'meta_description' => 'Home page',
                'user_id' => 1,
                'template' => 'home',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'about-us',
                'name' => 'About Us',
                'label' => 'About Us',
                'contents' => $aboutHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/artemissalt/images/banners/sub/image1.jpg',
                'meta_title' => 'About Us',
                'meta_keyword' => 'About Us',
                'meta_description' => 'About Us page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'contact-us',
                'name' => 'Contact Us',
                'label' => 'Contact Us',
                'contents' => $contact_us,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => \URL::to('/').'/theme/artemissalt/images/banners/sub/image1.jpg',
                'meta_title' => 'Contact Us',
                'meta_keyword' => 'Contact Us',
                'meta_description' => 'Contact Us page',
                'user_id' => 1,
                'template' => 'contact-us',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'news',
                'name' => 'News',
                'label' => 'News',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'customize',
                'image_url' => '',
                'meta_title' => 'News',
                'meta_keyword' => 'news',
                'meta_description' => 'News page',
                'user_id' => 1,
                'template' => 'news',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'footer',
                'name' => 'Footer',
                'label' => 'footer',
                'contents' => $footerHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];

        DB::table('pages')->insert($pages);
    }
}
