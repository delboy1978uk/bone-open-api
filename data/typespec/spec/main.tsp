import "@typespec/http";
import "@typespec/rest";
import "@typespec/openapi3";

import "./models/index.tsp";
import "./responses/index.tsp";
import "./payloads/index.tsp";

using TypeSpec.Http;
using TypeSpec.Rest;

@service(#{
  title: "Bone Framework API",
})
@server("/api", "API")
//@useAuth(BearerAuth)
namespace Application;

