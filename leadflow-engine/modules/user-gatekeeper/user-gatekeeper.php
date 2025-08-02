<?php
/**
 * Module: User Gatekeeper
 *
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once plugin_dir_path( __FILE__ ) . 'class-lfe-pending-users-list-table.php';

/**
 * Set user to 'pending' upon registration.
 *
 * @param int $user_id User ID.
 */
function lfe_user_register( $user_id ) {
    update_user_meta( $user_id, '_user_approval_status', 'pending' );

    $admin_email = get_option( 'admin_email' );
    $user = get_user_by( 'ID', $user_id );
    $subject = sprintf( __( '[%s] New User Registration Pending Approval', 'leadflow-engine' ), get_option( 'blogname' ) );
    $message = sprintf(
        __( 'A new user, %s (%s), has registered on your site and is waiting for approval.', 'leadflow-engine' ),
        $user->user_login,
        $user->user_email
    );
    $message .= "\r\n\r\n";
    $message .= __( 'You can approve or deny this user by visiting the Pending Users page:', 'leadflow-engine' );
    $message .= "\r\n";
    $message .= admin_url( 'users.php?page=pending-users' );

    wp_mail( $admin_email, $subject, $message );
}
add_action( 'user_register', 'lfe_user_register' );

/**
 * Prevent pending users from logging in.
 *
 * @param WP_User $user WP_User object.
 * @return WP_Error|WP_User
 */
function lfe_authenticate_user( $user ) {
    if ( isset( $user->ID ) && get_user_meta( $user->ID, '_user_approval_status', true ) === 'pending' ) {
        return new WP_Error( 'pending_approval', __( '<strong>ERROR</strong>: Your account is pending approval.', 'leadflow-engine' ) );
    }
    return $user;
}
add_filter( 'wp_authenticate_user', 'lfe_authenticate_user', 10, 1 );

/**
 * Add the pending users page to the Users menu.
 */
function lfe_add_pending_users_page() {
    add_users_page(
        __( 'Pending Users', 'leadflow-engine' ),
        __( 'Pending Users', 'leadflow-engine' ),
        'manage_options',
        'pending-users',
        'lfe_render_pending_users_page'
    );
}
add_action( 'admin_menu', 'lfe_add_pending_users_page' );

/**
 * Render the pending users page.
 */
function lfe_render_pending_users_page() {
    $list_table = new LFE_Pending_Users_List_Table();
    $list_table->prepare_items();
    ?>
    <div class="wrap">
        <h1><?php _e( 'Pending Users', 'leadflow-engine' ); ?></h1>
        <?php $list_table->display(); ?>
    </div>
    <?php
}

/**
 * Handle the approve/deny actions.
 */
function lfe_handle_pending_user_actions() {
    if ( isset( $_GET['page'] ) && $_GET['page'] === 'pending-users' ) {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : '';
        $user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;

        if ( $user_id > 0 ) {
            if ( $action === 'approve' && wp_verify_nonce( $_GET['_wpnonce'], 'lfe_approve_user_' . $user_id ) ) {
                lfe_approve_user( $user_id );
            }

            if ( $action === 'deny' && wp_verify_nonce( $_GET['_wpnonce'], 'lfe_deny_user_' . $user_id ) ) {
                lfe_deny_user( $user_id );
            }
        }
    }
}
add_action( 'admin_init', 'lfe_handle_pending_user_actions' );

/**
 * Approve a user.
 *
 * @param int $user_id User ID.
 */
function lfe_approve_user( $user_id ) {
    update_user_meta( $user_id, '_user_approval_status', 'approved' );
    $user = new WP_User( $user_id );
    $user->set_role( get_option( 'default_role' ) );
    wp_new_user_notification( $user_id, null, 'both' );
}

/**
 * Deny a user.
 *
 * @param int $user_id User ID.
 */
function lfe_deny_user( $user_id ) {
    wp_delete_user( $user_id );
}

/**
 * Registration form shortcode.
 */
function lfe_registration_form_shortcode() {
    ob_start();

    if ( isset( $_POST['lfe_register'] ) ) {
        $username = sanitize_user( $_POST['lfe_username'] );
        $email = sanitize_email( $_POST['lfe_email'] );
        $password = $_POST['lfe_password'];

        $errors = [];

        if ( username_exists( $username ) ) {
            $errors[] = __( 'Username already exists.', 'leadflow-engine' );
        }

        if ( email_exists( $email ) ) {
            $errors[] = __( 'Email already exists.', 'leadflow-engine' );
        }

        if ( empty( $errors ) ) {
            $user_id = wp_insert_user( [
                'user_login' => $username,
                'user_email' => $email,
                'user_pass'  => $password,
            ] );

            if ( ! is_wp_error( $user_id ) ) {
                echo '<div class="lfe-success">' . __( 'Registration successful. Please wait for admin approval.', 'leadflow-engine' ) . '</div>';
                return ob_get_clean();
            } else {
                $errors[] = $user_id->get_error_message();
            }
        }

        if ( ! empty( $errors ) ) {
            echo '<div class="lfe-error">';
            foreach ( $errors as $error ) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
    }

    ?>
    <div id="lfe-registration-form">
        <form action="" method="post">
            <p>
                <label for="lfe-username"><?php _e( 'Username', 'leadflow-engine' ); ?></label>
                <input type="text" name="lfe_username" id="lfe-username" required>
            </p>
            <p>
                <label for="lfe-email"><?php _e( 'Email', 'leadflow-engine' ); ?></label>
                <input type="email" name="lfe_email" id="lfe-email" required>
            </p>
            <p>
                <label for="lfe-password"><?php _e( 'Password', 'leadflow-engine' ); ?></label>
                <input type="password" name="lfe_password" id="lfe-password" required>
            </p>
            <p>
                <input type="submit" name="lfe_register" value="<?php _e( 'Register', 'leadflow-engine' ); ?>">
            </p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'lfe_registration_form', 'lfe_registration_form_shortcode' );
