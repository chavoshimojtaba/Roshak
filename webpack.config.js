const path = require('path')
module.exports = {
	entry:'./temp/admin/js/admin.js',
	mode:'development',
	output:{
		filename:'admin_app.js',
		path:path.resolve(__dirname,'./file/admin/js')
	},
	watch: true,
}