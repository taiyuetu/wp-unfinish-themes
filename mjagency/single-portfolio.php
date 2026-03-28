<?php get_header(); ?>

<main class="portfolio-single">
    <?php while (have_posts()):
    the_post();
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    $terms = get_the_terms(get_the_ID(), 'project_type');
?>
    
    <!-- Project Hero -->
    <section class="project-hero" <?php if ($thumbnail_url): ?>style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');"<?php
    endif; ?>>
        <div class="project-hero-overlay"></div>
        <div class="project-hero-content reveal">
            <div class="section-label">
                <?php if ($terms && !is_wp_error($terms)): ?>
                    <?php echo esc_html($terms[0]->name); ?>
                <?php
    endif; ?>
            </div>
            <h1 class="project-title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()): ?>
                <p class="project-excerpt"><?php echo get_the_excerpt(); ?></p>
            <?php
    endif; ?>
        </div>
        <div class="scroll-indicator">
            <div class="scroll-line"></div>
        </div>
    </section>

    <!-- Project Content -->
    <section class="project-body reveal">
        <div class="project-meta-sidebar">
            <div class="meta-item">
                <span class="meta-label">Client</span>
                <span class="meta-value"><?php echo strtok(get_the_title(), " "); ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Category</span>
                <span class="meta-value">
                    <?php if ($terms && !is_wp_error($terms)): ?>
                        <?php echo implode(', ', wp_list_pluck($terms, 'name')); ?>
                    <?php
    endif; ?>
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Year</span>
                <span class="meta-value"><?php echo get_the_date('Y'); ?></span>
            </div>
        </div>
        
        <div class="project-main-content">
            <?php the_content(); ?>
        </div>
    </section>

    <!-- Project Gallery / Visuals -->
    <?php
    $gallery = get_post_meta(get_the_ID(), 'page_gallery', true);
    if ($gallery):
        $gallery_ids = explode(',', $gallery);
?>
    <section class="project-gallery">
        <?php foreach ($gallery_ids as $img_id): ?>
            <div class="gallery-item reveal">
                <?php echo wp_get_attachment_image($img_id, 'full'); ?>
            </div>
        <?php
        endforeach; ?>
    </section>
    <?php
    endif; ?>

    <!-- Next Project Navigation -->
    <section class="project-nav reveal">
        <?php
    $next_post = get_next_post();
    if (!$next_post) {
        $next_post = get_posts(array('post_type' => 'portfolio', 'posts_per_page' => 1, 'order' => 'ASC'))[0] ?? null;
    }
    if ($next_post):
?>
        <a href="<?php echo get_permalink($next_post->ID); ?>" class="next-project-link">
            <span class="next-label">Next Project</span>
            <h2 class="next-title"><?php echo get_the_title($next_post->ID); ?></h2>
            <div class="next-arrow">→</div>
        </a>
        <?php
    endif; ?>
    </section>

    <?php
endwhile; ?>
</main>

<style>
.portfolio-single {
    background: var(--black);
    color: var(--offwhite);
}

.project-hero {
    height: 90vh;
    min-height: 600px;
    background-size: cover;
    background-position: center;
    position: relative;
    display: flex;
    align-items: flex-end;
    padding: 100px 48px;
    overflow: hidden;
}

.project-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(10,10,10,0.95) 0%, rgba(10,10,10,0.4) 50%, rgba(10,10,10,0.4) 100%);
}

.project-hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}

.project-title {
    font-family: var(--font-display);
    font-size: clamp(4rem, 10vw, 8rem);
    line-height: 0.9;
    margin: 20px 0;
    font-weight: 300;
}

.project-excerpt {
    font-size: 1.2rem;
    color: var(--steel);
    max-width: 500px;
    line-height: 1.6;
}

.project-body {
    padding: 120px 48px;
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 80px;
}

.meta-item {
    margin-bottom: 40px;
}

.meta-label {
    display: block;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    color: var(--champagne);
    margin-bottom: 8px;
}

.meta-value {
    font-size: 1rem;
    color: var(--offwhite);
    font-weight: 300;
}

.project-main-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--steel);
    max-width: 700px;
}

.project-main-content p {
    margin-bottom: 2rem;
}

.project-gallery {
    padding: 0 48px 120px;
    display: flex;
    flex-direction: column;
    gap: 80px;
}

.gallery-item img {
    width: 100%;
    height: auto;
    display: block;
}

.project-nav {
    padding: 120px 48px;
    border-top: 1px solid rgba(201, 185, 154, 0.1);
    text-align: center;
}

.next-project-link {
    text-decoration: none;
    display: inline-block;
    transition: transform 0.4s var(--ease-out);
}

.next-project-link:hover {
    transform: translateY(-10px);
}

.next-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    color: var(--champagne);
}

.next-title {
    font-family: var(--font-display);
    font-size: clamp(3rem, 6vw, 5rem);
    margin: 10px 0;
    color: var(--offwhite);
    font-weight: 300;
}

.next-arrow {
    font-size: 2rem;
    color: var(--champagne);
}

@media (max-width: 900px) {
    .project-body {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .project-hero {
        padding: 60px 24px;
    }
    .project-body, .project-gallery, .project-nav {
        padding: 80px 24px;
    }
}
</style>

<?php get_footer(); ?>
