import "@typespec/http";
import "@typespec/rest";
import "@typespec/openapi3";

import "./models/index.tsp";
import "./responses/index.tsp";
import "./payloads/index.tsp";

import "./vendors.tsp";

using TypeSpec.Http;
using TypeSpec.Rest;

@service(#{
  title: "Bone Framework API",
})
@server("/api", "API")

@useAuth(
  OAuth2Auth<[{
   type: OAuth2FlowType.clientCredentials;
   tokenUrl: "/oauth2/token";
   refreshUrl: "/oauth2/refresh";
   scopes: ["basic"];
 }]>
)
namespace Application;

// add routes here!

