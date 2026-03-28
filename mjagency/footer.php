<!-- Footer -->
<footer>
  <a href="<?php echo home_url(); ?>" class="footer-logo"><?php echo get_bloginfo('name'); ?></a>
  <p class="footer-copy">© <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved.</p>
  <nav class="footer-links">
    <a href="<?php echo home_url('/about'); ?>">About</a>
    <a href="<?php echo home_url('/contact'); ?>">Contact</a>
    <a href="<?php echo get_post_type_archive_link('portfolio'); ?>">Projects</a>
  </nav>
</footer>

<?php wp_footer(); ?>
</body>
</html>