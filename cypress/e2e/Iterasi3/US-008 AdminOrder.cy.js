// US-008 — Sebagai admin, saya ingin melihat, mencari, dan mengelola status
// semua pesanan pelanggan dari Admin Panel.
describe('Admin Order Management', () => {
    const loginAsAdmin = () => {
        cy.visit('/admin/login');
        cy.get('input[name="email"]').type('admin@sardiooptik.com');
        cy.get('input[name="password"]').type('password123');
        cy.contains('button', 'Login').click();
        cy.url().should('include', '/admin/dashboard');
    };

    // Bikin order asli lewat alur customer Cart -> Checkout (sama seperti
    // US-006 Checkout.cy.js), supaya data yang diverifikasi di Admin Panel
    // benar-benar berasal dari interaksi nyata, bukan data hasil seed.
    const createCustomerOrder = (customerName, whatsappNumber) => {
        cy.clearCookies();
        cy.visit('/products?category[]=Frame');
        cy.get('.so-catalog-card').first().invoke('attr', 'data-url').then((url) => {
            cy.visit(url);
            cy.get('button[name="buy_now"][value="0"]').click();
        });

        cy.get('#soCartCheckoutBtn').click();
        cy.get('input[name="_token"]').invoke('val').then((token) => {
            cy.get('input[name="selected_items[]"]').invoke('val').then((selectedKey) => {
                cy.request({
                    method: 'POST',
                    url: '/checkout',
                    form: true,
                    followRedirect: false,
                    body: {
                        _token: token,
                        'selected_items[]': selectedKey,
                        customer_name: customerName,
                        whatsapp_number: whatsappNumber,
                    },
                }).then((response) => {
                    const decodedMessage = decodeURIComponent(response.headers.location);
                    const orderCode = decodedMessage.match(/Kode Pesanan: (ORD-[A-Z0-9-]+)/)[1];
                    cy.wrap(orderCode).as('orderCode');
                });
            });
        });
    };

    beforeEach(() => {
        cy.clearCookies();
    });

    it('pengunjung yang belum login diarahkan ke Login Admin saat akses /admin/orders', () => {
        cy.visit('/admin/orders');

        cy.url().should('include', '/admin/login');
    });

    it('menampilkan daftar order lengkap dengan kolom ID Order, Customer, Total, Status, dan Tanggal', () => {
        loginAsAdmin();
        cy.visit('/admin/orders');

        cy.contains('.admin-page-head h1', 'Order Management').should('be.visible');
        ['ID Order', 'Customer', 'Total', 'Status', 'Tanggal'].forEach((col) => {
            cy.contains('.admin-table thead th', col).should('be.visible');
        });
    });

    it('order baru dari checkout customer langsung muncul di daftar Admin Orders', () => {
        createCustomerOrder('Cypress Admin Order Tester', '081234500111');

        cy.get('@orderCode').then((orderCode) => {
            loginAsAdmin();
            cy.visit('/admin/orders');
            cy.get('input[name="search"]').type(`${orderCode}{enter}`);

            cy.get('.admin-table tbody tr').should('have.length', 1);
            cy.contains('.admin-table tbody tr', orderCode).should('be.visible');
            cy.contains('.admin-table tbody tr', 'Cypress Admin Order Tester').should('be.visible');
        });
    });

    it('klik tombol Detail mengarahkan ke halaman Order Detail dengan data yang sesuai', () => {
        createCustomerOrder('Cypress Detail Order', '081234500133');

        cy.get('@orderCode').then((orderCode) => {
            loginAsAdmin();
            cy.visit('/admin/orders');
            cy.get('input[name="search"]').type(`${orderCode}{enter}`);
            cy.contains('.admin-table tbody tr', orderCode).contains('a', 'Detail').click();

            cy.url().should('include', '/admin/orders/');
            cy.contains('h1', orderCode).should('be.visible');
            cy.contains('Cypress Detail Order').should('be.visible');
            cy.contains('081234500133').should('be.visible');
            cy.get('.admin-table tbody tr').should('have.length.at.least', 1);
        });
    });

    it('update status order tersimpan dan tampil sebagai badge di daftar Order', () => {
        createCustomerOrder('Cypress Status Order', '081234500144');

        cy.get('@orderCode').then((orderCode) => {
            loginAsAdmin();
            cy.visit('/admin/orders');
            cy.get('input[name="search"]').type(`${orderCode}{enter}`);
            cy.contains('.admin-table tbody tr', orderCode).contains('a', 'Detail').click();

            cy.get('select[name="status"]').select('diproses');
            cy.contains('button', 'Simpan Status').click();

            cy.contains('Status pesanan diperbarui').should('be.visible');
            cy.get('select[name="status"]').should('have.value', 'diproses');

            cy.visit('/admin/orders');
            cy.get('input[name="search"]').type(`${orderCode}{enter}`);
            cy.contains('.admin-table tbody tr', orderCode).find('.admin-badge').should('contain.text', 'Diproses');
        });
    });
});
