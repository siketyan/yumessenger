package yumessenger.api

import io.gatling.core.Predef._
import io.gatling.core.structure.ChainBuilder
import io.gatling.http.Predef._

object Message {
  val create: ChainBuilder =
    exec(
      http("message.create")
        .post("/messages")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .body(
          StringBody(
            """
              {
                "text": "${message_text}"
              }
            """
              .stripMargin
          )
        )
        .check(status.is(201))
        .check(jsonPath("$.text").is("${message_text}"))
        .check(jsonPath("$.author.id").is("${user_id}"))
        .check(jsonPath("$.likes[*]").count.is(0))
        .check(jsonPath("$.createdAt").exists)
        .check(jsonPath("$.id").saveAs("message_id"))
    )

  val list: ChainBuilder =
    exec(
      http("message.list")
        .get("/messages")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .check(status.is(200))
        .check(jsonPath("$[*]").count.gte(1))
        .check(jsonPath("$[*].id").find(0).exists)
    )

  val delete: ChainBuilder =
    exec(
      http("message.delete")
        .delete("/messages/${message_id}")
        .header(HttpHeaderNames.Authorization, "Bearer ${token}")
        .check(status.is(204))
    )
}
