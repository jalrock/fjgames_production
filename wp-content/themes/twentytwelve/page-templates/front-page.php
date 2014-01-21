<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<?php global $current_user;
	$users = get_users();
	echo '<script>';
	echo 'var citymap = {};'. "\n";
	foreach ($users as $user) {
		$meta = get_user_meta( $user->ID );
		echo 'citymap[\''.strtolower($meta['user_city'][0]).'\'] = {
  				center: new google.maps.LatLng('.$meta['user_lat_lon'][0].')
			};'. "\n";
		// echo "<pre>";
		// print_r($meta);
		// echo "</pre>";
	}
	echo '</script>';

?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
<style>
      		#map-canvas {
		        min-height: 500px;

		     }
    </style>
    
    <script type="text/javascript">

var cityCircle;

function initialize() {
  // Create the map.
  var mapOptions = {
    zoom: 4,
    center: new google.maps.LatLng(38.5111, -96.8005),
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  // Construct the circle for each value in citymap.
  // Note: We scale the population by a factor of 20.

    for (var city in citymap) {
    	
	    var populationOptions = {
	      strokeColor: '#FF0000',
	      strokeOpacity: 0.8,
	      strokeWeight: 2,
	      fillColor: '#FF0000',
	      fillOpacity: 0.35,
	      map: map,
	      center: citymap[city].center,
	      radius: 60000
	    };

	    console.log(populationOptions)
	    // Add the circle for this city to the map.
	    cityCircle = new google.maps.Circle(populationOptions);
	}
	
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="map-canvas"></div>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-page-image">
						<?php the_post_thumbnail(); ?>
					</div><!-- .entry-page-image -->
				<?php endif; ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>