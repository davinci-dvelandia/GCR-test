<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use GRIM_SG\Settings;
use GRIM_SG\Sitemap;
use GRIM_SG\GoogleNews;
use GRIM_SG\ImageSitemap;
use GRIM_SG\VideoSitemap;

$template = $args['template'] ?? 'sitemap';
$title    = Sitemap::get_sitemap_title( $template );
$settings = get_option( Sitemap::$slug, new Settings() );

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<xsl:stylesheet version="1.1"
		xmlns:html="http://www.w3.org/TR/REC-html40"
		xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
		xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
		xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
		xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title><?php echo esc_html( $title ); ?></title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
				<meta name="robots" content="index,follow"/>
				<style type="text/css">
					body { font-family: 'Open Sans', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; text-align: center; }
					a { text-decoration: none; color: #2d89c7; }
					a:hover { border-bottom: 1px solid; }
					.header { background-color: #82a745; color: #fff; padding: 30px 30px 20px; }
					.header h1 { margin: 0; }
					.header a, .footer a { border-bottom: 1px solid; color: inherit; }
					.header a:hover, .footer a:hover { border-bottom: none; }
					.footer { color: #666; font-size: 12px; margin-bottom: 30px; }
					.top-content { margin: 10px auto -12px; }
					.top-content a:hover { border-bottom: none; }
					table { max-width: 1024px; margin: 20px auto; font-size: 12px; color: #444; }
					table tr:first-child { background-color: #fff !important; }
					table tr:nth-child(odd) { background-color: #ecf4db; }
					table tr th, table tr td { padding: 10px 15px; text-align: left; }
					table tr th { border-bottom: 1px solid #ccc; }
					img { max-height: 100px; max-width: 100px; }
					.image + .image { margin-top: 5px; }
					.loc-item + .loc-item { margin-top: 5px; }
				</style>
			</head>
			<body>
				<div class="header">
					<h1><?php echo esc_html( $title ); ?></h1>
					<?php if ( ! sgg_pro_enabled() ) : ?>
						<?php if ( $template === GoogleNews::$template ) : ?>
							<p>
								<a href="https://developers.google.com/search/docs/crawling-indexing/sitemaps/news-sitemap" target="_blank">Google News Sitemap</a> is generated by
								<a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Google XML Sitemaps Generator Plugin</a>
							</p>
							<p>Note that <strong>only posts from the last 48 hours</strong> will be processed by <a href="https://news.google.com" target="_blank">Google News</a>.</p>
						<?php elseif ( $template === ImageSitemap::$template ) : ?>
							<p>
								<a href="https://developers.google.com/search/docs/crawling-indexing/sitemaps/image-sitemaps" target="_blank">Image Sitemap</a> is generated by
								<a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Google XML Sitemaps Generator Plugin</a>
							</p>
						<?php elseif ( $template === VideoSitemap::$template ) : ?>
							<p>
								<a href="https://developers.google.com/search/docs/crawling-indexing/sitemaps/video-sitemaps" target="_blank">Video Sitemap</a> is generated by
								<a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Google XML Sitemaps Generator Plugin</a>
							</p>
						<?php else : ?>
							<p>XML Sitemap is generated by <a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Google XML Sitemaps Generator Plugin</a></p>
							<p>You can get more information on <a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Plugin page</a>.</p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div class="top-content">
					<?php
					if ( ( ! empty( $settings->sitemap_view ) && in_array( $template, array( ImageSitemap::$template, VideoSitemap::$template ), true ) )
						|| 'inner-sitemap' === $template ) {
						$sitemap_type = isset( $args['is_xml'] ) && ! $args['is_xml'] ? 'html' : 'xml';
						$sitemap_url  = 'xml' === $sitemap_type ? $settings->sitemap_url : $settings->html_sitemap_url;

						printf(
							/* translators: Sitemap Index URL */
							wp_kses_post( __( '<a href="%s">&#8592; Sitemap Index</a>', 'xml-sitemap-generator-for-google' ) ),
							esc_url( sgg_get_sitemap_url( $sitemap_url, "sitemap_{$sitemap_type}" ) )
						);
					}
					?>
				</div>
				<?php Sitemap::get_sitemap_table( $template ); ?>
				<?php if ( ! sgg_pro_enabled() ) : ?>
					<div class="footer">Generated by <a href="https://wpgrim.com/">WP Grim</a> -
						<a href="https://wordpress.org/plugins/xml-sitemap-generator-for-google/">Google XML Sitemaps Generator Plugin</a>
					</div>
				<?php endif; ?>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>