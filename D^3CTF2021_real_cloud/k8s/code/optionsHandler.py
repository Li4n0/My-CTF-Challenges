import flask

def main():
    resp = flask.Response("")
    resp.status_code = 204
    resp.headers['Access-Control-Allow-Origin'] = '*'
    resp.headers['Access-Control-Allow-Method'] = 'GET,HEAD,PUT,DELETE,OPTIONS'
    resp.headers['Access-Control-Allow-Headers'] = 'content-type'
    return resp
