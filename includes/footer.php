</div> <!-- End Main Content -->

    <?php
    // Deteksi path logo untuk footer
    if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/tambah/') !== false) {
        $footer_logo_path = '../../../assets/logo_sigawai.png';
    } 
    else if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/view/') !== false) {
        $footer_logo_path = '../../../assets/logo_sigawai.png';
    }
    else if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/edit/') !== false) {
        $footer_logo_path = '../../../assets/logo_sigawai.png';
    }
    else if (strpos($_SERVER['PHP_SELF'], '/pages/pegawai/') !== false) {
        $footer_logo_path = '../../assets/logo_sigawai.png';
    }
    else {
        $footer_logo_path = '../assets/logo_sigawai.png';
    }

    ?>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="footer-main">
            <div class="container-fluid px-4">
                <div class="row g-4">
                    <!-- Column 1: About -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <div class="footer-logo mb-3">
                                <img src="<?php echo $footer_logo_path; ?>" alt="SI-GAWAI" class="footer-logo-img">
                            </div>
                            <p class="footer-description">
                                Sistem ini digunakan untuk mengelola data pegawai di Kantor Imigrasi Kelas II TPI Lhokseumawe.
                            </p>
                            <div class="footer-contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Jalan Pelabuhan No. 5, Kota Lhokseumawe, Aceh.</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Navigation -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Navigation</h5>
                            <ul class="footer-links">
                                <li><a href="/karyawan-imigrasi/pages/dashboard.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                                <li><a href="/karyawan-imigrasi/pages/pegawai/list-kepegawaian.php"><i class="fas fa-chevron-right"></i> Pages</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> About Us</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Service</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Service</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 3: Quick Link -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Quick Link</h5>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> FAQs</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Gallery</a></li>
                                <li><a href="#"><i class="fas fa-chevron-right"></i> Pricing</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Column 4: Work House -->
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <h5 class="footer-title">Work House</h5>
                            <div class="work-hours mb-3">
                                <i class="far fa-clock"></i>
                                <span class="ms-2"><strong>7 AM - 8 PM, Mon-Fri</strong></span>
                            </div>
                            <p class="footer-description mb-3">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </p>
                            
                            <!-- Contact Button -->
                            <div class="mb-3">
                                <a href="tel:00000000000" class="btn-call-us">
                                    <i class="fas fa-phone-alt me-2"></i> Call Us
                                </a>
                            </div>

                            <!-- Social Media Icons -->
                            <div class="footer-social">
                                <a href="#" class="social-icon tiktok" title="TikTok">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                                <a href="#" class="social-icon instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="social-icon youtube" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a href="#" class="social-icon whatsapp" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        <p class="mb-0">&copy; <?php echo date('Y'); ?> Kantor Imigrasi Kelas II TPI Lhokseumawe. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-contact-info">
                            <a href="tel:00000000000" class="me-3">
                                <i class="fas fa-phone-alt me-1"></i> 0000 0000 0000
                            </a>
                            <a href="mailto:contact@gmail.com">
                                <i class="fas fa-envelope me-1"></i> Contact@gmail.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Footer Styles -->
    <style>
        .footer-section {
            background: linear-gradient(135deg, #24bce1ff 0%, #24bce1ff 100%);
            color: white;
            margin-left: 280px;
            margin-top: 50px;
        }

        .footer-main {
            padding: 50px 0 30px;
        }

        .footer-widget {
            padding: 0 15px;
        }

        .footer-logo img {
            max-width: 200px;
            height: auto;
        }

        .footer-logo-img {
            max-width: 200px;
            height: auto;
        }

        .footer-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: white;
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.85);
            font-size: 14px;
            line-height: 1.6;
        }

        .footer-contact-item {
            display: flex;
            align-items: start;
            gap: 12px;
            margin-top: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-contact-item i {
            font-size: 18px;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .footer-links a i {
            font-size: 10px;
        }

        .work-hours {
            display: flex;
            align-items: center;
            color: white;
            font-size: 14px;
        }

        .work-hours i {
            font-size: 20px;
        }

        .btn-call-us {
            display: inline-flex;
            align-items: center;
            padding: 10px 25px;
            background: white;
            color: #0891b2;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-call-us:hover {
            background: #f0f9ff;
            color: #0891b2;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .footer-social {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            color: #0891b2;
            border-radius: 10px;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .social-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .social-icon.tiktok:hover {
            background: #000000;
            color: white;
        }

        .social-icon.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
        }

        .social-icon.youtube:hover {
            background: #FF0000;
            color: white;
        }

        .social-icon.whatsapp:hover {
            background: #25D366;
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 0;
            font-size: 14px;
        }

        .footer-contact-info a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .footer-contact-info a:hover {
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .footer-section {
                margin-left: 0;
            }

            .footer-main {
                padding: 30px 0 20px;
            }

            .footer-title {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .footer-social {
                justify-content: center;
            }

            .btn-call-us {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            if ($('.dataTable').length) {
                $('.dataTable').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                    },
                    "pageLength": 10,
                    "responsive": true
                });
            }
        });
    </script>
</body>
</html>