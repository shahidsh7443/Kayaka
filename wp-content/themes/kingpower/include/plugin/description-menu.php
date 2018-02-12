<?php

class description_walker extends Walker_Nav_Menu{
      
	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {

		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';
		
		$icon_class = $item->classes[0]; unset($item->classes[0]);
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="'. esc_attr( $class_names ) . '"';

		$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		
		
		$prepend = '<span class="gdl-label">';
		$append = '</span>';
		$gdl_prepend = '<span class="gdl-des-prepend">';
		$gdl_append = '</span>';		
		$my_icon = ! empty( $icon_class ) ? '<i class="gdl-menu-icon ' . $icon_class . '" ></i>': '';
		$description = ! empty( $item->description ) ? '<span class="gdl-description">'.esc_attr( $item->description ).'</span>' : '';

		if($depth != 0){
			$description = $my_icon = $gdl_prepend = $gdl_append ="";
			$prepend = '<span class="gdl-sub-label">';
			$append = '</span>';
		}

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $my_icon;
		$item_output .= $gdl_prepend;
		$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
		$item_output .= $description.$args->link_after;
		$item_output .= $gdl_append;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

?>