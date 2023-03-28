<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.  The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php if ( post_password_required() ) : ?>
				<p class="nopassword">This post is password protected. Enter the password to view any comments.</p>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( '<span class="meta-nav">&larr;</span> Older Comments' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Newer Comments <span class="meta-nav">&rarr;</span>' ); ?></div>
			</div> <!-- .navigation -->
<?php endif; ?>

			<ul class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'site_review_comment' ) ); ?>
			</ul>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( '<span class="meta-nav">&larr;</span> Older Comments' ); ?></div>
				<div class="nav-next"><?php next_comments_link( 'Newer Comments <span class="meta-nav">&rarr;</span>' ); ?></div>
			</div><!-- .navigation -->
<?php endif; ?>

<?php else : // or, if we don't have comments:

	/* If there are no comments and comments are closed,
	 * let's leave a little note, shall we?
	 */
	if ( ! comments_open() ) :
?>
	<p class="nocomments">Comments are closed.</p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<?php $comment_args = array( 'fields' => apply_filters( 'comment_form_default_fields', array(
    'author' => '<table cellspacing="0"><tr><td class="tl"><label for="author">Name:</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '</td><td><input id="author" name="author" type="text" value="' .
                esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
				'</td></tr></table>',
    'email'  => '<table cellspacing="0"><tr><td class="tl"><label for="email">Email:</label> ' .
                ( $req ? '<span class="required">*</span>' : '' ) .
                '</td><td><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' . 
				'</td></tr></table>',
    'url'    => '' ) ),
    'comment_field' => '<table cellspacing="0"><tr><td class="tl" valign="top"><label for="comment">Comment:</label> </td>' .
                '<td><textarea id="comment" name="comment" aria-required="true"></textarea>' .
				'</td></tr></table>',
    'comment_notes_after' => '',
);
comment_form($comment_args);

?>
</table>