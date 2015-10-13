<?php
/**
 * Template Name: Loggers
 * The template for displaying logger's graphics
 *
 * TODO: Revisar si el logger existe
 *       Quitar decimales a algunos campos
 */

get_header(); ?>

  <div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">

    <?php if ( have_posts() ) : ?>

      <?php if( get_query_var( 'logger' ) ): ?>
        <header class="archive-header">
          <h1 class="archive-title">Escoge una estación</h1>

          <div class="archive-meta">
            <?php windpexplorer_the_logger_selector(); ?>
          </div>
        </header><!-- .archive-header -->
      <?php endif; ?>

      <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
            <div class="entry-thumbnail">
              <?php the_post_thumbnail(); ?>
            </div>
            <?php endif; ?>

            <?php if ( is_single() ) : ?>
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php else : ?>
            <h1 class="entry-title">
              <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h1>
            <?php endif; // is_single() ?>

            <div class="entry-meta">
              <?php twentythirteen_entry_meta(); ?>
              <?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
            </div><!-- .entry-meta -->
          </header><!-- .entry-header -->

          <div class="entry-content">
            <?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>

            <?php if( get_query_var( 'logger' ) ): ?>

              <?php
                $loggerID = get_query_var( 'logger' );

                $logger = windpexplorer_get_logger_by_id( $loggerID );

                $sensors = windpexplorer_get_sensors( $loggerID );
              ?>

              <h3><?php echo $logger->location; ?></h3>

              <p><?php echo get_post_meta( get_the_ID(), $logger->location, true ); ?></p>

              <?php foreach( $sensors as $sensor ): ?>

                <?php get_template_part( 'sensor', sanitize_title( $sensor->description ) ); ?>

              <?php endforeach; ?>

            <?php else: ?>

            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentythirteen' ) ); ?>
            
              <p>Estaciones metereológicas disponibles:</p>

              <?php
              $loggers = windpexplorer_get_loggers();

              $output = '<ul>';

              foreach( $loggers as $logger )
              {
                $output.= '<li><a href="' . add_query_arg( 'logger', $logger->id, get_permalink() ) . '">' . $logger->location . '</a></li>';
              }

              $output.= '</ul>';

              echo $output;
              ?>
            <?php endif; ?>
          </div><!-- .entry-content -->

          <footer class="entry-meta">
            <?php if ( comments_open() && ! is_single() ) : ?>
              <div class="comments-link">
                <?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a comment', 'twentythirteen' ) . '</span>', __( 'One comment so far', 'twentythirteen' ), __( 'View all % comments', 'twentythirteen' ) ); ?>
              </div><!-- .comments-link -->
            <?php endif; // comments_open() ?>

            <?php if ( is_single() && get_the_author_meta( 'description' ) && is_multi_author() ) : ?>
              <?php get_template_part( 'author-bio' ); ?>
            <?php endif; ?>
          </footer><!-- .entry-meta -->
        </article><!-- #post -->

      <?php endwhile; ?>

      <?php twentythirteen_paging_nav(); ?>

    <?php else : ?>
      <?php get_template_part( 'content', 'none' ); ?>
    <?php endif; ?>

    </div><!-- #content -->
  </div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>