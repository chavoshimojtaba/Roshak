const path = require('path')
module.exports = {
	entry:'./temp/admin/js/auth.js',
	mode:'production',
	output:{
		filename:'auth_app.js',
		path:path.resolve(__dirname,'./file/admin/js')
	},
	watch: true,
}