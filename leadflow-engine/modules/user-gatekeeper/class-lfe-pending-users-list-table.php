<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class LFE_Pending_Users_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => __( 'Pending User', 'leadflow-engine' ),
            'plural'   => __( 'Pending Users', 'leadflow-engine' ),
            'ajax'     => false
        ] );
    }

    public function get_columns() {
        $columns = [
            'username' => __( 'Username', 'leadflow-engine' ),
            'email'    => __( 'Email', 'leadflow-engine' ),
            'reg_date' => __( 'Registered', 'leadflow-engine' )
        ];
        return $columns;
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [ $columns, $hidden, $sortable ];

        $args = [
            'meta_key' => '_user_approval_status',
            'meta_value' => 'pending'
        ];
        $user_query = new WP_User_Query( $args );
        $this->items = $user_query->get_results();
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'email':
                return $item->user_email;
            case 'reg_date':
                return $item->user_registered;
            default:
                return print_r( $item, true );
        }
    }

    public function column_username( $item ) {
        $actions = [
            'approve' => sprintf(
                '<a href="%s">%s</a>',
                esc_url( wp_nonce_url( admin_url( 'users.php?page=pending-users&action=approve&user_id=' . $item->ID ), 'lfe_approve_user_' . $item->ID ) ),
                __( 'Approve', 'leadflow-engine' )
            ),
            'deny' => sprintf(
                '<a href="%s">%s</a>',
                esc_url( wp_nonce_url( admin_url( 'users.php?page=pending-users&action=deny&user_id=' . $item->ID ), 'lfe_deny_user_' . $item->ID ) ),
                __( 'Deny', 'leadflow-engine' )
            ),
        ];

        return sprintf( '%1$s %2$s', $item->user_login, $this->row_actions( $actions ) );
    }
}
