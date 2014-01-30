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
<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="entry-page-image">
						<?php the_post_thumbnail(); ?>
					</div><!-- .entry-page-image -->
				<?php endif; ?>

				<?php get_template_part( 'content', 'page' ); ?>

			<?php endwhile; // end of the loop. ?>
<style>
.site {
	width:100% !important;
	min-width:100% !important;
	padding: 0 !important;	
}
.site-header {
	padding-left:40px !important;
}
.entry-header {
	padding-left:40px !important;
}
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<?php global $current_user;
	$locations = $wpdb->get_results( "SELECT * FROM wppl_friends_locator" );
	echo '<script>';
	echo 'var citymap = {};'. "\n";
	foreach ($locations as $location) {
		echo 'citymap[\''.$location->member_id.'\'] = {
  				center: new google.maps.LatLng('.$location->lat.', '.$location->long.')
			};'. "\n";
	}
	echo '</script>';

?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
<style>
      		#map-canvas {
		        min-height: 700px;

		     }
    </style>
    
    <script type="text/javascript">

var cityCircle;

function initialize() {
  // Create the map.
  var mapOptions = {
    zoom: 5,
    center: new google.maps.LatLng(38.726188, -96.930918),
    mapTypeId: google.maps.MapTypeId.SATELLITE
  };

  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  // Construct the circle for each value in citymap.
  // Note: We scale the population by a factor of 20.
  console.log(citymap)
    for (var city in citymap) {
    	
	    var populationOptions = {
	      strokeColor: '#FF0000',
	      strokeOpacity: 0.8,
	      strokeWeight: 2,
	      fillColor: '#FF0000',
	      fillOpacity: 0.35,
	      map: map,
	      center: citymap[city].center,
	      radius: 10000
	    };

	    console.log(populationOptions)
	    // Add the circle for this city to the map.
	    cityCircle = new google.maps.Circle(populationOptions);
	}
	
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div id="map-canvas"></div>
			

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar( 'front' ); ?>
<?php get_footer(); ?>